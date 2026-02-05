<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $destinosActivos  = DB::table('sitio')->count();
        $serviciosTotales = DB::table('hotel')->count() + DB::table('restaurante')->count();
        $reservasTotales  = DB::table('reserva_hotel')->count();

        return view('admin.dashboard', compact(
            'destinosActivos',
            'serviciosTotales',
            'reservasTotales'
        ));
    }
}
