@extends('layouts.admin')
@section('title', 'Catálogo de Recomendaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="ea-page-title">Recomendaciones</h1>
    <a href="{{ route('admin.recomendaciones.create') }}" class="btn ea-btn-green">+ Nueva recomendación</a>
</div>
@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
<div class="ea-card p-0 overflow-hidden">
    <table class="table align-middle mb-0">
        <thead><tr><th>ID</th><th>Descripción</th><th class="text-end">Acciones</th></tr></thead>
        <tbody>
            @foreach($recomendaciones as $rec)
            <tr>
                <td>{{ $rec->id_recomendacion }}</td>
                <td>{{ $rec->descripcion }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.recomendaciones.edit', $rec->id_recomendacion) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                    <form action="{{ route('admin.recomendaciones.destroy', $rec->id_recomendacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection