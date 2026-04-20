@extends('layouts.admin')
@section('title', 'Editar Recomendación')
@section('content')
<div class="mb-4">
    <h1 class="ea-page-title">Editar Recomendación</h1>
</div>
<form action="{{ route('admin.recomendaciones.update', $recomendacion->id_recomendacion) }}" method="POST">
    @csrf @method('PUT')
    <div class="ea-card p-4">
        <label class="form-label fw-bold">Descripción *</label>
        <textarea name="descripcion" class="form-control" rows="3" required maxlength="150">{{ old('descripcion', $recomendacion->descripcion) }}</textarea>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn ea-btn-green">Actualizar</button>
        <a href="{{ route('admin.recomendaciones.index') }}" class="btn btn-light border">Cancelar</a>
    </div>
</form>
@endsection