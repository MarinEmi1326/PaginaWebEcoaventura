<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class AdminCategoriaController extends Controller
{

public function index()
{
    $categorias = Categoria::all();

    return view('admin.categorias.index', compact('categorias'));
}


public function store(Request $request)
{
    Categoria::create([
        'nombre' => $request->nombre
    ]);

    return redirect()->back();
}


public function destroy($id)
{
    Categoria::destroy($id);

    return redirect()->back();
}

}
