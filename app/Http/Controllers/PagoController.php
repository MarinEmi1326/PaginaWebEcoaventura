<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Mail\ConfirmacionPagoTurista;
use App\Mail\NotificacionPagoAdmin;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PagoController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // GET /paquetes/{id}/pagar
    public function show($id_paquete)
    {
        $paquete = DB::table('paquete')
            ->where('id_paquete', $id_paquete)
            ->where('activo', 'activo')
            ->first();

        if (!$paquete) abort(404);

        $destino = DB::table('destino')
            ->where('id_destino', $paquete->id_destino)
            ->first();

        return view('pagos.checkout', compact('paquete', 'destino'));
    }

    // POST /paquetes/{id}/pagar
    public function procesar(Request $request, $id_paquete)
    {
        $paquete = DB::table('paquete')
            ->where('id_paquete', $id_paquete)
            ->where('activo', 'activo')
            ->first();

        if (!$paquete) abort(404);

        $user    = auth()->user();
        $turista = DB::table('turista')->where('id_usuario', $user->id_usuario)->first();

        if (!$turista) {
            return back()->with('error', 'Solo los turistas pueden adquirir paquetes.');
        }

        try {
            $monto = intval($paquete->precio * 100);

            $intent = PaymentIntent::create([
                'amount'               => $monto,
                'currency'             => 'mxn',
                'payment_method'       => $request->payment_method_id,
                'confirmation_method'  => 'manual',
                'confirm'              => true,
                'return_url'           => route('pagos.confirmacion', $id_paquete),
                'metadata' => [
                    'paquete'    => $paquete->nombre,
                    'id_turista' => $turista->id_turista,
                ],
            ]);

            
            $id_pago = DB::table('pago')->insertGetId([
                'id_turista'            => $turista->id_turista,
                'id_paquete'            => $paquete->id_paquete,
                'id_destino'            => $paquete->id_destino,
                'personas'              => $request->personas ?? 1,
                'stripe_payment_intent' => $intent->id,
                'monto'                 => $paquete->precio,
                'moneda'                => 'mxn',
                'estado'                => $intent->status === 'succeeded' ? 'completado' : 'pendiente',
                'fecha'                 => now(),
            ]);

            if ($intent->status === 'succeeded') {
                $pago    = DB::table('pago')->where('id_pago', $id_pago)->first();
                $destino = DB::table('destino')->where('id_destino', $paquete->id_destino)->first();

                // Correo al turista
                $correoTurista = DB::table('usuario')
                    ->where('id_usuario', $user->id_usuario)
                    ->value('correo');

                Mail::to($correoTurista)
                    ->send(new ConfirmacionPagoTurista($pago, $paquete, $destino, $turista));

                // Correo al admin_destinos dueño del destino
                $adminDestinos = DB::table('usuario')
                    ->where('id_usuario', $destino->creado_por)
                    ->first();

                if ($adminDestinos) {
                    Mail::to($adminDestinos->correo)
                        ->send(new NotificacionPagoAdmin($pago, $paquete, $destino, $turista));

                    // Notificación FCM al admin_destinos
                    if ($adminDestinos->fcm_token) {
                        $this->enviarNotificacionFCM(
                            $adminDestinos->fcm_token,
                            '💰 Nuevo pago recibido',
                            "{$turista->nombre} {$turista->apaterno} adquirió el paquete {$paquete->nombre} de {$destino->nombre}",
                            [
                                'id_pago'    => (string) $id_pago,
                                'id_destino' => (string) $destino->id_destino,
                                'tipo'       => 'pago',
                            ]
                        );
                    }
                }

                return redirect()->route('pagos.confirmacion', $id_paquete)
                    ->with('success', '¡Pago realizado con éxito!');
            }

            return back()->with('error', 'El pago no pudo procesarse. Intenta de nuevo.');
        } catch (\Stripe\Exception\CardException $e) {
            return back()->with('error', 'Tarjeta rechazada: ' . $e->getError()->message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    // GET /paquetes/{id}/confirmacion
    public function confirmacion($id_paquete)
    {
        $paquete = DB::table('paquete')->where('id_paquete', $id_paquete)->first();
        $destino = DB::table('destino')->where('id_destino', $paquete->id_destino)->first();

        return view('pagos.confirmacion', compact('paquete', 'destino'));
    }

    // Enviar notificación FCM
    private function enviarNotificacionFCM(string $token, string $titulo, string $cuerpo, array $data = [])
    {
        try {
            $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
            $messaging = $factory->createMessaging();

            $message = CloudMessage::fromArray([
                'token' => $token,
                'notification' => [
                    'title' => $titulo,
                    'body'  => $cuerpo,
                ],
                'data' => $data,
            ]);

            $messaging->send($message);
        } catch (\Exception $e) {
            \Log::error('Error FCM: ' . $e->getMessage());
        }
    }
}
