@extends('layouts.admin')
@section('title', 'Nueva Recomendación')
@section('content')
    <div class="mb-4">
        <h1 class="ea-page-title">Nueva Recomendación</h1>
    </div>

    <form action="{{ route('admin.recomendaciones.store') }}" method="POST">
        @csrf

        <div class="ea-card p-4">
            <label class="form-label fw-bold fs-5">Descripción de la recomendación *</label>
            <textarea name="descripcion" id="descripcionRecomendacion" class="form-control form-control-lg py-3 border-2"
                style="background-color: #fef9e6; border-color: #8a827b; font-size: 1rem;" rows="4" required maxlength="150"
                placeholder="Ej. No recolectar plantas ni perturbar la fauna silvestre"></textarea>
            <div class="form-text text-muted mt-2">Máximo 150 caracteres.</div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn ea-btn-green">Guardar</button>
            <a href="{{ route('admin.recomendaciones.index') }}" class="btn btn-light border">Cancelar</a>
        </div>
    </form>

    {{-- SCRIPT --}}
    <script>
        // SOLO LETRAS (incluye acentos y espacios)
        document.getElementById('descripcionRecomendacion').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
        });
    </script>

@endsection
