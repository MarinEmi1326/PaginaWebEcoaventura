<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadController extends Controller
{
    public function index()
    {
        $actividades = DB::table('actividad')->orderBy('nombre')->get();
        return view('admin.actividades.index', compact('actividades'));
    }

    public function create()
    {
        return view('admin.actividades.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:actividad,nombre|max:80']);
        DB::table('actividad')->insert(['nombre' => $request->nombre]);
        return redirect()->route('admin.actividades.index')->with('success', 'Actividad creada.');
    }

    public function edit($id)
    {
        $actividad = DB::table('actividad')->where('id_actividad', $id)->first();
        abort_if(!$actividad, 404);
        return view('admin.actividades.edit', compact('actividad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required|unique:actividad,nombre,'.$id.',id_actividad|max:80']);
        DB::table('actividad')->where('id_actividad', $id)->update(['nombre' => $request->nombre]);
        return redirect()->route('admin.actividades.index')->with('success', 'Actividad actualizada.');
    }

    public function destroy($id)
    {
        $usada = DB::table('destino_actividad')->where('id_actividad', $id)->exists();
        if ($usada) return back()->with('error', 'No se puede eliminar porque hay destinos que usan esta actividad.');
        DB::table('actividad')->where('id_actividad', $id)->delete();
        return back()->with('success', 'Actividad eliminada.');
    }
}