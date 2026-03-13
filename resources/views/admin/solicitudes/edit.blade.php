@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">

      {{-- Encabezado --}}
      <div class="mb-4">
        <a href="{{ route('admin.solicitudes.index') }}"
           class="text-decoration-none fw-semibold"
           style="color: var(--ea-green);">
          ← Volver al listado
        </a>

        <h1 class="ea-page-title mt-3 mb-1">Editar Usuario</h1>
        <p class="ea-subtitle mb-0">
          Modifica la información de la cuenta de <b>{{ $usuario->correo }}</b>
        </p>
      </div>

      <form action="{{ route('admin.solicitudes.update', $usuario->id_usuario) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- BLOQUE 1 --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
          <div class="p-4 border-bottom"
               style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
            <div class="fw-semibold" style="color: var(--ea-text);">
              🔑 Credenciales y Acceso
            </div>
          </div>

          <div class="p-4">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="correo" value="{{ $usuario->correo }}" required
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password"
                       placeholder="Dejar en blanco para no cambiar"
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Rol</label>

                @if($usuario->rol === 'turista')
                  {{-- Turista: solo lectura, no se puede cambiar de rol --}}
                  <input type="hidden" name="rol" value="turista">
                  <input type="text" value="Turista" disabled
                         class="form-control rounded-3 py-2"
                         style="background: rgba(0,0,0,.05); color: var(--ea-muted);">
                @else
                  <select name="rol" class="form-select rounded-3 py-2" required>
                    <option value="admin_destinos" {{ $usuario->rol == 'admin_destinos' ? 'selected' : '' }}>
                        Administrador de destinos
                    </option>
                    <option value="gestor_rutas" {{ $usuario->rol == 'gestor_rutas' ? 'selected' : '' }}>
                        Gestor de rutas
                    </option>
                  </select>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- BLOQUE 2 --}}
        <div class="ea-card p-0 overflow-hidden">
          <div class="p-4 border-bottom"
               style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
            <div class="fw-semibold" style="color: var(--ea-text);">
              👤 Información Personal
            </div>
          </div>

          <div class="p-4">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Nombre(s)</label>
                <input type="text" name="nombre" value="{{ $usuario->nombre ?? '' }}" required
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Apellido Paterno</label>
                <input type="text" name="apaterno" value="{{ $usuario->apaterno ?? '' }}" required
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Apellido Materno</label>
                <input type="text" name="amaterno" value="{{ $usuario->amaterno ?? '' }}"
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Teléfono</label>
                <input type="text" name="telefono" value="{{ $usuario->telefono ?? '' }}" required
                       class="form-control rounded-3 py-2 font-monospace">
              </div>
            </div>
          </div>
        </div>

        {{-- Acciones --}}
        <div class="d-flex justify-content-end mt-4 pt-4 border-top"
             style="border-color: rgba(15,42,36,.10) !important;">
          <button type="submit" class="btn ea-btn-green px-4 py-2 rounded-3 fw-semibold">
            Actualizar Datos del Usuario
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection