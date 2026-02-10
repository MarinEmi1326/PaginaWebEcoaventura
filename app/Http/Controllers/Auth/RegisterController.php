<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Turista;
use App\Models\Hotelero;
use App\Models\Restaurantero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\VerificacionTuristaMail;
use App\Mail\SolicitudPendienteMail;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Maneja el registro de nuevos usuarios.
     */
    public function register(Request $request)
    {
        // ✅ Validación (condicional por rol)
        $request->validate([
            'rol'      => ['required', Rule::in(['turista', 'hotelero', 'restaurantero'])],
            'nombre'   => ['required', 'string'],
            'apaterno' => ['required', 'string'],
            'amaterno' => ['required', 'string'], // tú lo pediste required
            'correo'   => ['required', 'email', 'unique:usuario,correo'],
            'password' => ['required', 'confirmed', 'min:6'],
            'terms'    => ['accepted'],

            // Teléfono SOLO para hotelero/restaurantero
            'telefono' => ['required_if:rol,hotelero,restaurantero', 'nullable', 'digits:10'],

            // Hotel (solo hotelero)
            'nombre_hotel'    => ['required_if:rol,hotelero'],
            'direccion_hotel' => ['required_if:rol,hotelero'],

            // Restaurante (solo restaurantero)
            'nombre_restaurante'    => ['required_if:rol,restaurantero'],
            'direccion_restaurante' => ['required_if:rol,restaurantero'],
        ], [
            'terms.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        $user = null;

        DB::transaction(function () use ($request, &$user) {

            // ✅ Si es hotelero/restaurantero => solicitud pendiente y NO activo
            $esSolicitud = in_array($request->rol, ['hotelero', 'restaurantero']);

            $user = Usuario::create([
                'correo' => $request->correo,
                'password' => bcrypt($request->password),
                'rol' => $request->rol,
                'activo' => $esSolicitud ? 0 : 1,
                'estado' => $esSolicitud ? 'pendiente' : 'aprobado',
                'fecha_solicitud' => $esSolicitud ? now() : null,
            ]);

            if ($request->rol === 'turista') {
               $turista = Turista::create([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'id_usuario' => $user->id_usuario,
                ]);

                 
                 // Enviar correo de verificación al turista
                Mail::to($user->correo)->send(new VerificacionTuristaMail($turista));
                // Loguear al usuario
                Auth::login($user);
                return redirect()->route('home')->with('success', 'Cuenta de turista registrada correctamente.');
            }

            if ($request->rol === 'hotelero') {
                $hotelero = Hotelero::create([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'telefono' => $request->telefono,
                    'id_usuario' => $user->id_usuario,
                    
                ]);

                 //Crear hotel asociado (campos obligatorios => defaults)
                DB::table('hotel')->insert([
                    'nombre'     => $request->nombre_hotel,
                    'direccion'  => $request->direccion_hotel,
                    'descripcion'=> 'Pendiente de completar',
                    'telefono'   => $request->telefono,
                    'foto'       => 'img/Hoteles/default.png',
                    'id_hotelero'=> $hotelero->id_hotelero,
                ]);
               // Enviamos el correo pasando el objeto $user
                Mail::to($user->correo)->send(new SolicitudPendienteMail($user));

                return redirect()->route('login')->with('info', 'Tu solicitud ha sido enviada. Espera la aprobación del administrador.');
            }

            if ($request->rol === 'restaurantero') {
                $restaurantero = Restaurantero::create([
                    'nombre' => $request->nombre,
                    'apaterno' => $request->apaterno,
                    'amaterno' => $request->amaterno,
                    'telefono' => $request->telefono,
                    'id_usuario' => $user->id_usuario,
                    
                ]);

                 //  Crear restaurante asociado (campos obligatorios => defaults)
                DB::table('restaurante')->insert([
                    'nombre'          => $request->nombre_restaurante,
                    'descripcion'     => 'Pendiente de completar',
                    'direccion'       => $request->direccion_restaurante,
                    'horario'         => 'Pendiente',
                    'telefono'        => $request->telefono,
                    'foto'            => 'img/Restaurantes/default.png',
                    'id_restaurantero'=> $restaurantero->id_restaurantero,
                ]);

                // Enviamos el correo pasando el objeto $user
                Mail::to($user->correo)->send(new SolicitudPendienteMail($user));

                return redirect()->route('login')->with('info', 'Tu solicitud ha sido enviada. Espera la aprobación del administrador.');
            }
        });

        if (!$user) {
            return back()->withErrors(['register' => 'No se pudo crear el usuario.']);
        }

        //  Si es solicitud: NO loguear, mostrar mensaje
        if (in_array($user->rol, ['hotelero', 'restaurantero'])) {
            return redirect()->route('login')->with(
                'success',
                'Tu solicitud fue enviada. Un administrador revisará tus datos y te llegará un correo cuando sea aprobada o rechazada.'
            );
        }

        // ✅ Turista sí entra normal
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Cuenta creada correctamente. ¡Bienvenido!');
    }

}
