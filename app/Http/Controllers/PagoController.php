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

class PagoController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ==========================================
    // Calcular rango permitido de personas para un paquete
    // ==========================================
    private function calcularRangoPersonasPaquete($idPaquete)
    {
        $actividades = DB::table('paquete_actividad')
            ->where('id_paquete', $idPaquete)
            ->select('minimo_personas', 'maximo_personas')
            ->get();

        if ($actividades->isEmpty()) {
            return ['min' => 1, 'max' => 0]; // Sin límite superior
        }

        $minGlobal = $actividades->max('minimo_personas');
        $maxGlobal = $actividades->min('maximo_personas');

        // Si la configuración es inválida (min > max), asumimos solo mínimo
        if ($maxGlobal > 0 && $minGlobal > $maxGlobal) {
            return ['min' => $minGlobal, 'max' => 0];
        }

        return ['min' => $minGlobal, 'max' => $maxGlobal];
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

        // Horarios ocupados
        $horariosOcupados = DB::table('pago')
            ->where('id_paquete', $id_paquete)
            ->where('estado', 'completado')
            ->select('fecha_visita', 'horario')
            ->get()
            ->groupBy('fecha_visita')
            ->map(fn($items) => $items->pluck('horario')->toArray());

        // Rango permitido de personas
        $rango = $this->calcularRangoPersonasPaquete($id_paquete);

        return view('pagos.checkout', compact('paquete', 'destino', 'horariosOcupados', 'rango'));
    }

    // POST /paquetes/{id}/pagar
    public function procesar(Request $request, $id_paquete)
    {
        $paquete = DB::table('paquete')
            ->where('id_paquete', $id_paquete)
            ->where('activo', 'activo')
            ->first();

        if (!$paquete) abort(404);

        // Validaciones básicas
        $request->validate([
            'fecha_visita' => 'required|date|after:today',
            'personas'     => 'required|integer|min:1',
            'horario'      => 'required|string',
        ]);

        // 1. Validar rango de personas según actividades del paquete
        $rango = $this->calcularRangoPersonasPaquete($id_paquete);
        $personas = $request->personas;

        if ($personas < $rango['min']) {
            return back()->with('error', "El número mínimo de personas para este paquete es {$rango['min']}.")->withInput();
        }
        if ($rango['max'] > 0 && $personas > $rango['max']) {
            return back()->with('error', "El número máximo de personas para este paquete es {$rango['max']}.")->withInput();
        }

        // 2. Validar disponibilidad de fecha y horario
        $fecha_visita = $request->fecha_visita;
        $horario = $request->horario;

        $reservaExistente = DB::table('pago')
            ->where('id_paquete', $id_paquete)
            ->where('fecha_visita', $fecha_visita)
            ->where('horario', $horario)
            ->where('estado', 'completado')
            ->exists();

        if ($reservaExistente) {
            return back()->with('error', 'Lo sentimos, este horario ya está reservado. Por favor, elige otra fecha u horario.')
                ->withInput();
        }

        $user    = auth()->user();
        $persona = DB::table('persona')->where('id_usuario', $user->id_usuario)->first();

        if (!$persona) {
            return back()->with('error', 'Perfil no encontrado.');
        }

        try {
            $monto = intval($paquete->precio * 100);

            $intent = PaymentIntent::create([
                'amount'              => $monto,
                'currency'            => 'mxn',
                'payment_method'      => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm'             => true,
                'return_url'          => route('pagos.confirmacion', $id_paquete),
                'metadata' => [
                    'paquete'    => $paquete->nombre,
                    'id_persona' => $persona->id_persona,
                ],
            ]);

            $id_pago = DB::table('pago')->insertGetId([
                'id_persona'            => $persona->id_persona,
                'id_paquete'            => $paquete->id_paquete,
                'id_destino'            => $paquete->id_destino,
                'personas'              => $personas,
                'fecha_visita'          => $fecha_visita,
                'horario'               => $horario,
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
                Mail::to($correoTurista)->send(new ConfirmacionPagoTurista($pago, $paquete, $destino, $persona));

                // Notificación FCM al turista
                if ($user->fcm_token) {
                    $this->enviarNotificacionFCM(
                        $user->fcm_token,
                        '🎉 Pago exitoso',
                        "Has adquirido el paquete {$paquete->nombre} para {$destino->nombre} el {$fecha_visita} a las {$horario}",
                        ['id_pago' => (string) $id_pago, 'id_destino' => (string) $destino->id_destino, 'tipo' => 'pago_turista']
                    );
                }

                // Notificar al admin dueño del destino
                $creadorDestino = DB::table('persona')->where('id_persona', $destino->creado_por)->first();
                if ($creadorDestino) {
                    $usuarioAdmin = DB::table('usuario')->where('id_usuario', $creadorDestino->id_usuario)->first();
                    if ($usuarioAdmin) {
                        Mail::to($usuarioAdmin->correo)->send(new NotificacionPagoAdmin($pago, $paquete, $destino, $persona));
                        if ($usuarioAdmin->fcm_token) {
                            $this->enviarNotificacionFCM(
                                $usuarioAdmin->fcm_token,
                                '💰 Nuevo pago recibido',
                                "{$persona->nombre} {$persona->apellidos} adquirió el paquete {$paquete->nombre} de {$destino->nombre} para el {$fecha_visita} a las {$horario}",
                                ['id_pago' => (string) $id_pago, 'id_destino' => (string) $destino->id_destino, 'tipo' => 'pago_admin']
                            );
                        }
                    }
                }

                return redirect()->route('pagos.confirmacion', $id_paquete)
                    ->with('success', '¡Pago realizado con éxito!');
            }

            return back()->with('error', 'El pago no pudo procesarse. Intenta de nuevo.');
        } catch (\Stripe\Exception\CardException $e) {
            return back()->with('error', 'Tarjeta rechazada: ' . $e->getError()->message);
        } catch (\Exception $e) {
            \Log::error('Error en pago: ' . $e->getMessage());
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

    // Enviar notificación FCM (mismo código)
    private function enviarNotificacionFCM(string $token, string $titulo, string $cuerpo, array $data = [])
    {
        try {
            $factory   = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
            $messaging = $factory->createMessaging();
            $message = CloudMessage::fromArray([
                'token'        => $token,
                'notification' => ['title' => $titulo, 'body' => $cuerpo],
                'data'         => $data,
            ]);
            $messaging->send($message);
        } catch (\Exception $e) {
            \Log::error('Error FCM: ' . $e->getMessage());
        }
    }
}