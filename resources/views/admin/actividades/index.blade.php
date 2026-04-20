@extends('layouts.admin')
@section('title', 'Catálogo de Actividades')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="ea-page-title">Actividades</h1>
    <a href="{{ route('admin.actividades.create') }}" class="btn ea-btn-green">+ Nueva actividad</a>
</div>
@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
<div class="ea-card p-0 overflow-hidden">
    <table class="table align-middle mb-0">
        <thead><tr><th>ID</th><th>Nombre</th><th class="text-end">Acciones</th></tr></thead>
        <tbody>
            @foreach($actividades as $act)
            <tr>
                <td>{{ $act->id_actividad }}</td>
                <td>{{ $act->nombre }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.actividades.edit', $act->id_actividad) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                    <form action="{{ route('admin.actividades.destroy', $act->id_actividad) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
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