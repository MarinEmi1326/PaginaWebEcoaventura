@extends('layouts.admin')
@section('title', 'Editar Actividad')
@section('content')
<div class="mb-4">
    <h1 class="ea-page-title">Editar Actividad</h1>
</div>
<form action="{{ route('admin.actividades.update', $actividad->id_actividad) }}" method="POST">
    @csrf @method('PUT')
    <div class="ea-card p-4">
    <label class="form-label fw-bold fs-5">Nombre de la actividad *</label>
    <input type="text" 
           name="nombre" 
           value="{{ old('nombre', $actividad->nombre) }}"
           class="form-control form-control-lg py-3 border-2" 
           style="background-color: #fef9e6; border-color: #8a827b; font-size: 1.1rem;"
           required 
           maxlength="80">
</div>
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn ea-btn-green">Actualizar</button>
        <a href="{{ route('admin.actividades.index') }}" class="btn btn-light border">Cancelar</a>
    </div>
</form>
@endsection