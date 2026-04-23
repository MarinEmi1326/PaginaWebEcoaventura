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
                <p class="ea-subtitle mb-0">
                    El usuario creado desde este panel quedará aprobado y con acceso inmediato.
                </p>
            </div>

            {{-- Error general --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4"
                     style="background: rgba(209,75,58,.10); color:#d14b3a;">
                    <div class="fw-semibold mb-2">Revisa los campos del formulario:</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.solicitudes.store') }}" method="POST" novalidate>
                @csrf

                {{-- BLOQUE 1 --}}
                <div class="ea-card p-0 overflow-hidden mb-4">
                    <div class="p-4 border-bottom"
                         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                        <div class="fw-semibold" style="color: var(--ea-text);">
                            🔑 Datos de Acceso al Sistema
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Correo electrónico</label>
                                <input type="email"
                                       id="correo"
                                       name="correo"
                                       value="{{ old('correo') }}"
                                       required
                                       placeholder="ejemplo@correo.com"
                                       class="form-control rounded-3 py-2 @error('correo') is-invalid @enderror">
                                <div id="correoError" class="text-danger small mt-1 d-none">
                                    Correo no válido
                                </div>
                                @error('correo')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Rol del usuario</label>
                                <select name="rol"
                                        required
                                        class="form-select rounded-3 py-2 @error('rol') is-invalid @enderror">
                                    <option value="">Seleccione un rol</option>
                                    <option value="admin_destinos" {{ old('rol') == 'admin_destinos' ? 'selected' : '' }}>
                                        Administrador de destinos
                                    </option>
                                    <option value="gestor_rutas" {{ old('rol') == 'gestor_rutas' ? 'selected' : '' }}>
                                        Gestor de rutas
                                    </option>
                                </select>
                                @error('rol')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PASSWORD --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Contraseña</label>
                                <div class="input-group">
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           required
                                           placeholder="••••••••"
                                           class="form-control rounded-3 py-2 @error('password') is-invalid @enderror">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">👁</button>
                                </div>
                                @error('password')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Confirmar contraseña</label>
                                <div class="input-group">
                                    <input type="password"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required
                                           placeholder="••••••••"
                                           class="form-control rounded-3 py-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">👁</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- BLOQUE 2 --}}
                <div class="ea-card p-0 overflow-hidden mb-4">
                    <div class="p-4 border-bottom"
                         style="border-color: var(--ea-line) !important;">
                        <div class="fw-semibold">👤 Información Personal del Usuario</div>
                    </div>

                    <div class="p-4">
                        <div class="row g-3">

                            {{-- NOMBRE SOLO LETRAS --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Nombre(s)</label>
                                <input type="text"
                                       id="nombre"
                                       name="nombre"
                                       value="{{ old('nombre') }}"
                                       required
                                       placeholder="Ingrese nombres"
                                       class="form-control rounded-3 py-2 @error('nombre') is-invalid @enderror">
                                @error('nombre')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- APELLIDOS SOLO LETRAS --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Apellidos</label>
                                <input type="text"
                                       id="apellidos"
                                       name="apellidos"
                                       value="{{ old('apellidos') }}"
                                       required
                                       placeholder="Ej. Pérez García"
                                       class="form-control rounded-3 py-2 @error('apellidos') is-invalid @enderror">
                                @error('apellidos')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- TELÉFONO --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Número de teléfono / WhatsApp</label>
                                <input type="text"
                                       name="telefono"
                                       id="telefono"
                                       value="{{ old('telefono') }}"
                                       required
                                       maxlength="10"
                                       placeholder="Ej: 9671234567"
                                       class="form-control rounded-3 py-2 font-monospace @error('telefono') is-invalid @enderror">
                                @error('telefono')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                    <button type="reset" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
                        🧹 Limpiar formulario
                    </button>

                    <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
                        ✅ Guardar y habilitar usuario
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
// Mostrar contraseña
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

// Correo validación
document.getElementById('correo').addEventListener('input', function() {
    const error = document.getElementById('correoError');
    error.classList.toggle('d-none', this.value.includes('@'));
});

// Teléfono solo números
document.getElementById('telefono').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// SOLO LETRAS (incluye acentos y espacios)
function soloLetras(input) {
    input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
}

document.getElementById('nombre').addEventListener('input', function() {
    soloLetras(this);
});

document.getElementById('apellidos').addEventListener('input', function() {
    soloLetras(this);
});

// Bloquear envío si correo inválido
document.querySelector('form').addEventListener('submit', function(e) {
    const correo = document.getElementById('correo');
    if (!correo.value.includes('@')) {
        e.preventDefault();
        document.getElementById('correoError').classList.remove('d-none');
        correo.focus();
    }
});
</script>

@endsection