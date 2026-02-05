<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // Obtener al usuario autenticado
        return view('perfil', compact('user')); // Pasar los datos del usuario a la vista
    }

    public function update(Request $request)
    {
        // Validar los campos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $user = Auth::user(); // Obtener al usuario autenticado
        $user->update([
            'nombre' => $request->nombre,
            'apaterno' => $request->apaterno,
            'amaterno' => $request->amaterno,
            'telefono' => $request->telefono,
        ]);

        return back()->with('success', 'Información actualizada correctamente');
    }
}
