<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $destinosActivos = DB::table('destino')->count();
        $rutasTotales    = DB::table('ruta')->count();
        $actividadesTotales = DB::table('actividad')->count();

        return view('admin.index', compact(
            'destinosActivos',
            'rutasTotales',
            'actividadesTotales'
        ));
    }
}