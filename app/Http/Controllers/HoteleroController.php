<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
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
        // Obtener el usuario logueado
        $user = Auth::user(); 

        // Obtener las reservas de este hotelero
        $reservas = Reserva::where('hotelero_id', $user->id_usuario)->get();

        // Obtener las habitaciones de este hotelero
        $habitaciones = Habitacion::where('hotelero_id', $user->id_usuario)->get();

        // Obtener los servicios de este hotelero
        $servicios = Servicio::where('hotelero_id', $user->id_usuario)->get();

        // Pasar los datos a la vista
        return view('hotelero.dashboard', compact('reservas', 'habitaciones', 'servicios'));
    }

    /**
     * Muestra las reservas del hotelero.
     */
    public function reservas()
    {
        $user = Auth::user();
        $reservas = Reserva::where('hotelero_id', $user->id_usuario)->get();
        return view('hotelero.reservas', compact('reservas'));
    }

    /**
     * Muestra las habitaciones del hotelero.
     */
    public function habitaciones()
    {
        $user = Auth::user();
        $habitaciones = Habitacion::where('hotelero_id', $user->id_usuario)->get();
        return view('hotelero.habitaciones', compact('habitaciones'));
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
}
