@extends('layouts.admin')

@section('title', 'Nueva Actividad')

@section('content')
<form action="{{ route('admin.actividades.store') }}" method="POST">
    @csrf

    <div class="ea-card p-4">
        <label for="nombre" class="form-label fw-bold fs-5">Nombre de la actividad *</label>
        <input type="text"
               id="nombre"
               name="nombre"
               value="{{ old('nombre') }}"
               class="form-control form-control-lg py-3 border-2"
               style="background-color: #fef9e6; border-color: #8a827b; font-size: 1.1rem;"
               required
               maxlength="80"
               placeholder="Ej. Avistamiento de mariposas monarca">

        <div class="form-text text-muted mt-2">
            Escribe el nombre de la actividad (único).
        </div>

        @error('nombre')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn ea-btn-green">Guardar</button>
        <a href="{{ route('admin.actividades.index') }}" class="btn btn-light border">Cancelar</a>
    </div>
</form>

<script>
    // Validación: solo letras (incluye acentos, espacios y eñes)
    document.getElementById('nombre').addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
    });
</script>
@endsection