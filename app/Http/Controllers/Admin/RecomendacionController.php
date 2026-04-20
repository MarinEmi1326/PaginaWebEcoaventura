<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecomendacionController extends Controller
{
    public function index()
    {
        $recomendaciones = DB::table('recomendacion')->orderBy('descripcion')->get();
        return view('admin.recomendaciones.index', compact('recomendaciones'));
    }

    public function create()
    {
        return view('admin.recomendaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate(['descripcion' => 'required|unique:recomendacion,descripcion|max:150']);
        DB::table('recomendacion')->insert([
            'descripcion' => $request->descripcion,
            'tipo'        => 'general',
            'activo'      => 1,
        ]);
        return redirect()->route('admin.recomendaciones.index')->with('success', 'Recomendación creada.');
    }

    public function edit($id)
    {
        $recomendacion = DB::table('recomendacion')->where('id_recomendacion', $id)->first();
        abort_if(!$recomendacion, 404);
        return view('admin.recomendaciones.edit', compact('recomendacion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['descripcion' => 'required|unique:recomendacion,descripcion,'.$id.',id_recomendacion|max:150']);
        DB::table('recomendacion')->where('id_recomendacion', $id)->update(['descripcion' => $request->descripcion]);
        return redirect()->route('admin.recomendaciones.index')->with('success', 'Recomendación actualizada.');
    }

    public function destroy($id)
    {
        $usada = DB::table('destino_recomendacion')->where('id_recomendacion', $id)->exists()
              || DB::table('ruta_recomendacion')->where('id_recomendacion', $id)->exists();
        if ($usada) return back()->with('error', 'No se puede eliminar porque hay destinos o rutas que usan esta recomendación.');
        DB::table('recomendacion')->where('id_recomendacion', $id)->delete();
        return back()->with('success', 'Recomendación eliminada.');
    }
}