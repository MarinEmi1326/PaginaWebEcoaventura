@extends('layouts.admin')

@section('title', 'Crear Nuevo Usuario')

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

        <h1 class="ea-page-title mt-3 mb-1">Registrar Nuevo Usuario</h1>
        <p class="ea-subtitle mb-0">El usuario creado tendrá acceso inmediato al sistema.</p>
      </div>

      <form action="{{ route('admin.solicitudes.store') }}" method="POST">
        @csrf

        {{-- BLOQUE 1: Credenciales --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
          <div class="p-4 border-bottom"
               style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
            <div class="fw-semibold" style="color: var(--ea-text);">🔑 Datos de Acceso al Sistema</div>
          </div>

          <div class="p-4">
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="correo" value="{{ old('correo') }}" required
                       placeholder="ejemplo@correo.com"
                       class="form-control rounded-3 py-2">
                @error('correo') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Rol del Usuario</label>
                <select name="rol" required class="form-select rounded-3 py-2">
                  <option value="hotelero">🏨 Hotelero</option>
                  <option value="restaurantero">🍴 Restaurantero</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- BLOQUE 2: Información personal --}}
        <div class="ea-card p-0 overflow-hidden">
          <div class="p-4 border-bottom"
               style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
            <div class="fw-semibold" style="color: var(--ea-text);">👤 Información Personal del Solicitante</div>
          </div>

          <div class="p-4">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Nombre(s)</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                       placeholder="Ingrese nombres"
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-3">
                <label class="form-label fw-bold">Apellido Paterno</label>
                <input type="text" name="apaterno" value="{{ old('apaterno') }}" required
                       placeholder="Apellido 1"
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-3">
                <label class="form-label fw-bold">Apellido Materno</label>
                <input type="text" name="amaterno" value="{{ old('amaterno') }}"
                       placeholder="Apellido 2"
                       class="form-control rounded-3 py-2">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">📞 Número de Teléfono / WhatsApp</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" required
                       placeholder="Ej: 9671234567"
                       class="form-control rounded-3 py-2 font-monospace">
              </div>
            </div>
          </div>
        </div>

        {{-- Acciones --}}
        <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-4 pt-4 border-top"
             style="border-color: rgba(15,42,36,.10) !important;">
          <button type="reset"
                  class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
            🧹 Limpiar Formulario
          </button>

          <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
            ✅ Guardar y Habilitar Usuario
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection