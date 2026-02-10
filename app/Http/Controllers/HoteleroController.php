<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Hotelero;
use App\Models\Reserva;
use App\Models\Turista;
use App\Models\Habitacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoteleroController extends Controller
{
    /**
     * Muestra el Dashboard del hotelero con las reservas, habitaciones y servicios.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $hotelero = Hotelero::where('id_usuario', $user->id_usuario)->first();

        if ($hotelero && $hotel = Hotel::where('id_hotelero', $hotelero->id_hotelero)->first()) {
            $habitaciones = Habitacion::where('id_hotel', $hotel->id_hotel)->get();
            
            // Obtenemos las reservas mediante las habitaciones
            $reservas = Reserva::whereIn('id_habitacion', $habitaciones->pluck('id_habitacion'))->get();
            
            // ¡Aquí obtenemos los servicios vinculados al hotel!
            $servicios = $hotel->servicios; 
        } else {
            $hotel = null;
            $habitaciones = collect();
            $reservas = collect();
            $servicios = collect();
        }

        $hideNavbar = true; // Agregamos esto
        return view('hotelero.index', compact('hotel', 'reservas', 'habitaciones', 'servicios', 'hideNavbar'));
    }
    /**
     * Muestra las reservas del hotelero.
     */
    public function reservas()
    {
        $user = Auth::user();
        $hotelero = Hotelero::where('id_usuario', $user->id_usuario)->first();
        $hotel = Hotel::where('id_hotelero', $hotelero->id_hotelero)->first();

        if ($hotel) {
            $idsHabitaciones = Habitacion::where('id_hotel', $hotel->id_hotel)->pluck('id_habitacion');
            // Usamos with() para traer los datos del turista y la habitación sin hacer 100 consultas
            $reservas = Reserva::with(['turista', 'habitacion'])
                                ->whereIn('id_habitacion', $idsHabitaciones)
                                ->get();
        } else {
            $reservas = collect();
        }

        $hideNavbar = true;
        return view('hotelero.reservas', compact('reservas', 'hideNavbar'));
    }

    public function createReserva() {
    $user = Auth::user();
    $hotelero = Hotelero::where('id_usuario', $user->id_usuario)->first();
    
    // Si esto sale null, el usuario no es hotelero en la tabla hotelero
    if(!$hotelero) dd("No eres hotelero en la tabla hotelero");

    $hotel = Hotel::where('id_hotelero', $hotelero->id_hotelero)->first();
    
   

    $turistas = Turista::all();
    $habitaciones = Habitacion::where('id_hotel', $hotel->id_hotel)
                              ->where('estado', 'disponible')
                              ->get();

    // Si esto sale [], las habitaciones existen pero no están ligadas al id_hotel: $hotel->id_hotel
    if($habitaciones->isEmpty()) dd("No hay habitaciones disponibles para el hotel ID: " . $hotel->id_hotel);

    return view('hotelero.reservas.create', compact('turistas', 'habitaciones'));
    }

    public function storeReserva(Request $request) {
    // 1. Validamos los datos que vienen del formulario
    $request->validate([
        'id_turista' => 'required|exists:turista,id_turista',
        'id_habitacion' => 'required|exists:habitacion,id_habitacion',
        'fecha_entrada' => 'required|date|after_or_equal:today',
        'fecha_salida' => 'required|date|after:fecha_entrada',
    ]);

    // 2. Creamos la reserva en la tabla 'reserva_hotel'
    // El modelo Reserva ya sabe que la tabla se llama 'reserva_hotel'
    $reserva = Reserva::create([
        'id_turista' => $request->id_turista,
        'id_habitacion' => $request->id_habitacion,
        'fecha_entrada' => $request->fecha_entrada,
        'fecha_salida' => $request->fecha_salida,
        'estado' => 'pendiente', // Estado inicial por defecto
        'id_pago' => null        // Lo dejamos nulo por ahora
    ]);

    // 3. (Opcional) Si quieres que la habitación pase a 'ocupada' de inmediato
    // Habitacion::where('id_habitacion', $request->id_habitacion)->update(['estado' => 'ocupada']);

    return redirect()->route('hotelero.reservas')->with('success', '¡Reserva creada exitosamente!');
    }
    /**
     * Ver detalles de una reserva específica.
     */
    public function showReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        return view('hotelero.reservas_show', compact('reserva'));
    }

    /**
     * Aprobar una reserva (Cambia estado a Confirmada).
     */
    public function aprobarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = 'confirmada'; // Asegúrate que este nombre coincida con tu BD
        $reserva->save();

        return back()->with('success', 'La reserva ha sido confirmada.');
    }

    /**
     * Rechazar una reserva (Cambia estado a Cancelada).
     */
    public function rechazarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = 'cancelada'; // O 'rechazada', según tu lógica
        $reserva->save();

        return back()->with('error', 'La reserva ha sido rechazada.');
    }

    /**
     * Muestra las habitaciones del hotelero.
     */
    public function habitaciones()
    {
        $user = Auth::user();
        $hotelero = Hotelero::where('id_usuario', $user->id_usuario)->first();

        if ($hotelero) {
            $hotel = Hotel::where('id_hotelero', $hotelero->id_hotelero)->first();
            
            if ($hotel) {
                $habitaciones = Habitacion::where('id_hotel', $hotel->id_hotel)->get();
                
                // AGREGA ESTA LÍNEA para obtener las reservas de esas habitaciones
                $reservas = Reserva::whereIn('id_habitacion', $habitaciones->pluck('id_habitacion'))->get();
            } else {
                $habitaciones = collect();
                $reservas = collect(); // Variable vacía para que no de error
            }
        } else {
            return redirect()->back()->with('error', 'Perfil no encontrado.');
        }

        $hideNavbar = true;
        // Agrega 'reservas' al compact
        return view('hotelero.habitaciones', compact('habitaciones', 'reservas', 'hideNavbar'));
    }

    /**
     * Muestra los servicios del hotelero.
     */
    public function servicios()
    {
        $user = Auth::user();
        $servicios = Servicio::where('hotelero_id', $user->id_usuario)->get();
        return view('hotelero.servicios', compact('servicios'));
    }

    public function perfil()
    {
        // Pasamos el usuario logueado como "hotelero" para que la vista lo reconozca
        $hotelero = Auth::user(); 
        return view('hotelero.perfil', compact('hotelero'));
    }

    public function updatePerfil(Request $request)
    {
        // Aquí iría la lógica para guardar los cambios del perfil
        // Por ahora puedes dejarlo así para que no falle la ruta:
        return back()->with('success', 'Perfil actualizado correctamente');
    }
}
