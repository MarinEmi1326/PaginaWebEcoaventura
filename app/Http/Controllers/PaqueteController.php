<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaqueteController extends Controller
{
    private function getPersonaId()
    {
        $persona = DB::table('persona')->where('id_usuario', Auth::id())->first();
        return $persona ? $persona->id_persona : null;
    }

    private function verificarDestino($idDestino)
    {
        $personaId = $this->getPersonaId();
        $destino = DB::table('destino')
            ->where('id_destino', $idDestino)
            ->where('creado_por', $personaId)
            ->first();
        abort_if(!$destino, 404, 'Destino no encontrado o no pertenece a este usuario.');
        return $destino;
    }

    public function index($idDestino)
    {
        $destino = $this->verificarDestino($idDestino);
        $paquetes = DB::table('paquete')
            ->where('id_destino', $idDestino)
            ->orderBy('id_paquete')
            ->get();
        return view('admin.paquetes.index', compact('destino', 'paquetes'));
    }

    public function create($idDestino)
    {
        $destino = $this->verificarDestino($idDestino);
        $actividades = DB::table('actividad')->orderBy('nombre')->get();
        return view('admin.paquetes.create', compact('destino', 'actividades'));
    }

    public function store(Request $request, $idDestino)
    {
        $destino = $this->verificarDestino($idDestino);

        $request->validate([
            'nombre'          => 'required|max:120',
            'descripcion'     => 'nullable',
            'precio'          => 'nullable|numeric|min:0',
            'tipo_publico'    => 'required|in:todo,especifico',
            'edad_minima'     => 'nullable|integer|min:0',
            'edad_maxima'     => 'nullable|integer|min:0',
            'actividades'     => 'array',
            'actividades.*.id_actividad' => 'exists:actividad,id_actividad',
            'actividades.*.minimo' => 'nullable|integer|min:1',
            'actividades.*.maximo' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $idPaquete = DB::table('paquete')->insertGetId([
                'id_destino'     => $idDestino,
                'nombre'         => $request->nombre,
                'descripcion'    => $request->descripcion,
                'precio'         => $request->precio,
                'tipo_publico'   => $request->tipo_publico,
                'edad_minima'    => $request->tipo_publico == 'especifico' ? $request->edad_minima : null,
                'edad_maxima'    => $request->tipo_publico == 'especifico' ? $request->edad_maxima : null,
                'activo'         => 'activo',
            ]);

            if ($request->filled('actividades')) {
                $orden = 0;
                foreach ($request->actividades as $act) {
                    $orden++;
                    DB::table('paquete_actividad')->insert([
                        'id_paquete'       => $idPaquete,
                        'id_actividad'     => $act['id_actividad'],
                        'minimo_personas'  => $act['minimo'] ?? 1,
                        'maximo_personas'  => $act['maximo'] ?? 100,
                        'orden'            => $orden,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('destinos.paquetes.index', $idDestino)
                ->with('success', 'Paquete creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function edit($idDestino, $idPaquete)
    {
        $destino = $this->verificarDestino($idDestino);
        $paquete = DB::table('paquete')->where('id_paquete', $idPaquete)->where('id_destino', $idDestino)->first();
        abort_if(!$paquete, 404);

        $actividadesDisponibles = DB::table('actividad')->orderBy('nombre')->get();
        $actividadesDelPaquete = DB::table('paquete_actividad')
            ->where('id_paquete', $idPaquete)
            ->orderBy('orden')
            ->get();

        return view('admin.paquetes.edit', compact('destino', 'paquete', 'actividadesDisponibles', 'actividadesDelPaquete'));
    }

    public function update(Request $request, $idDestino, $idPaquete)
    {
        $destino = $this->verificarDestino($idDestino);
        $paquete = DB::table('paquete')->where('id_paquete', $idPaquete)->where('id_destino', $idDestino)->first();
        abort_if(!$paquete, 404);

        $request->validate([
            'nombre'          => 'required|max:120',
            'descripcion'     => 'nullable',
            'precio'          => 'nullable|numeric|min:0',
            'tipo_publico'    => 'required|in:todo,especifico',
            'edad_minima'     => 'nullable|integer|min:0',
            'edad_maxima'     => 'nullable|integer|min:0',
            'actividades'     => 'array',
            'actividades.*.id_actividad' => 'exists:actividad,id_actividad',
            'actividades.*.minimo' => 'nullable|integer|min:1',
            'actividades.*.maximo' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            DB::table('paquete')->where('id_paquete', $idPaquete)->update([
                'nombre'         => $request->nombre,
                'descripcion'    => $request->descripcion,
                'precio'         => $request->precio,
                'tipo_publico'   => $request->tipo_publico,
                'edad_minima'    => $request->tipo_publico == 'especifico' ? $request->edad_minima : null,
                'edad_maxima'    => $request->tipo_publico == 'especifico' ? $request->edad_maxima : null,
            ]);

            DB::table('paquete_actividad')->where('id_paquete', $idPaquete)->delete();
            if ($request->filled('actividades')) {
                $orden = 0;
                foreach ($request->actividades as $act) {
                    $orden++;
                    DB::table('paquete_actividad')->insert([
                        'id_paquete'       => $idPaquete,
                        'id_actividad'     => $act['id_actividad'],
                        'minimo_personas'  => $act['minimo'] ?? 1,
                        'maximo_personas'  => $act['maximo'] ?? 100,
                        'orden'            => $orden,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('destinos.paquetes.index', $idDestino)
                ->with('success', 'Paquete actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($idDestino, $idPaquete)
    {
        $this->verificarDestino($idDestino);
        $paquete = DB::table('paquete')->where('id_paquete', $idPaquete)->where('id_destino', $idDestino)->first();
        abort_if(!$paquete, 404);
        DB::table('paquete_actividad')->where('id_paquete', $idPaquete)->delete();
        DB::table('paquete')->where('id_paquete', $idPaquete)->delete();
        return redirect()->route('destinos.paquetes.index', $idDestino)
            ->with('success', 'Paquete eliminado.');
    }
}