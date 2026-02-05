<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestauranteroController extends Controller
{
    public function dashboard()
    {
        return view('restaurantero.dashboard'); // Vista para el dashboard del restaurantero
    }

    public function menu()
    {
        return view('restaurantero.menu'); // Vista para el menú del restaurantero
    }

    public function reservas()
    {
        return view('restaurantero.reservas'); // Vista para las reservas del restaurantero
    }

    public function opiniones()
    {
        return view('restaurantero.opiniones'); // Vista para las opiniones del restaurantero
    }
}
