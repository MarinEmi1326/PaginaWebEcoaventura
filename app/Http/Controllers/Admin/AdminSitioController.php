<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class AdminSitioController extends Controller
{
    public function index(Request $request)
    {
        $categoria = $request->get('categoria'); // Turistico / Ecoturistico / Balneario
        $buscar = $request->get('buscar');       // texto para buscar por nombre

        $sitios = DB::table('sitio')
            ->when($categoria, function ($q) use ($categoria) {
                return $q->where('categoria', $categoria);
            })
            ->when($buscar, function ($q) use ($buscar) {
                return $q->where('nombre', 'LIKE', '%' . $buscar . '%');
            })
            ->orderByDesc('id_sitio')
            ->get();

        return view('admin.sitios.index', compact('sitios', 'categoria', 'buscar'));
    }

    public function create(Request $request)
    {
        // Si vienes desde el listado filtrado, capturamos la categoría
        $categoria = $request->get('categoria'); // Turistico / Ecoturistico / Balneario
        return view('admin.sitios.create', compact('categoria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => ['required'],  
            'descripcion' => ['required'],  
            'direccion'   => ['required'],  
            'horario'     => ['required'],
            'telefono'    => ['required', 'max:10'], 
            'categoria'   => ['required', 'in:Balneario,Ecoturistico,Turistico'],
            'comunidad'   => ['required'],
            'ciudad'      => ['required'],
            'costo'        => ['required', 'numeric', 'min:0'],
            'foto'         => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 
            'info_guia'    => ['nullable'],  
        ]);


        $ruta = $request->file('foto')->store('img/sitio', 'public');

        DB::table('sitio')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'horario' => $request->horario,
            'telefono' => $request->telefono,
            'categoria' => $request->categoria,
            'comunidad' => $request->comunidad,
            'ciudad' => $request->ciudad,
            'costo' => $request->costo,
            'foto' => $ruta,
            'info_guia' => $request->info_guia,
        ]);

        return redirect()->route('admin.sitios.index', [
                'categoria' => $request->categoria,
                'buscar' => $request->get('buscar')
            ])
            ->with('ok', 'Destino creado correctamente.');
    }

    public function edit(Request $request, $id)
    {
        $sitio = DB::table('sitio')->where('id_sitio', $id)->first();
        abort_if(!$sitio, 404);

        // Si viene filtro por URL, úsalo; si no, usa el del sitio
        $categoria = $request->get('categoria') ?? $sitio->categoria;

        return view('admin.sitios.edit', compact('sitio', 'categoria'));
    }

    public function update(Request $request, $id)
    {
        // Obtener el sitio
        $sitio = DB::table('sitio')->where('id_sitio', $id)->first();
        abort_if(!$sitio, 404);

        // Validación de los datos
        $validated = $request->validate([
            'nombre' => ['required'],
            'descripcion' => ['required'],
            'direccion' => ['required'],
            'horario' => ['required'],
            'telefono' => ['required', 'max:10'],
            'categoria' => ['required', 'in:Balneario,Ecoturistico,Turistico'],
            'comunidad' => ['required'],
            'ciudad' => ['required'],
            'costo' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Foto opcional
            'info_guia' => ['nullable'],
        ]);

        // Ruta de la foto actual (si no cambia)
        $path = $sitio->foto;

        // Si suben una nueva foto
        if ($request->hasFile('foto')) {
            // Usamos el método 'store' que maneja el nombre y ruta automáticamente
            $path = $request->file('foto')->store('sitios', 'public');

            // Borrar la foto anterior si existe
            if (!empty($sitio->foto) && file_exists(public_path($sitio->foto))) {
                @unlink(public_path($sitio->foto));
            }
        }

        // Actualizar los datos en la base de datos
        DB::table('sitio')->where('id_sitio', $id)->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'direccion' => $validated['direccion'],
            'horario' => $validated['horario'],
            'telefono' => $validated['telefono'],
            'categoria' => $validated['categoria'],
            'comunidad' => $validated['comunidad'],
            'ciudad' => $validated['ciudad'],
            'costo' => $validated['costo'],
            'foto' => $path,
            'info_guia' => $validated['info_guia'],
        ]);

        // Redirigir al listado de sitios con mensaje de éxito
        return redirect()->route('admin.sitios.index', [
                'categoria' => $request->categoria,
                'buscar' => $request->get('buscar')
            ])
            ->with('ok', 'Destino actualizado correctamente.');
    }

     public function destroy($id)
    {
        $sitio = DB::table('sitio')->where('id_sitio', $id)->first();

        if (!$sitio) {
            return back()->with('error', 'El destino no existe.');
        }

        //  borrar imagen física
        if ($sitio->foto && File::exists(public_path($sitio->foto))) {
            File::delete(public_path($sitio->foto));
        }

        //  borrar registro
        DB::table('sitio')->where('id_sitio', $id)->delete();

        return redirect()->route('admin.sitios.index', [
            'categoria' => request('categoria'),
            'buscar' => request('buscar')
        ])->with('ok', 'Destino eliminado correctamente.');

    }
}
