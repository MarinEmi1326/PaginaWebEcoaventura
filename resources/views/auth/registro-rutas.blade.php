@extends('layouts.app')

@php $hideNavbar = true; @endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7 col-xl-6">

            <div class="mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                    ← Volver al inicio
                </a>
            </div>

            <div class="bg-white rounded-4 shadow-sm border p-4 p-md-5">

                <h2 class="fw-bold mb-2">Registro — Gestor de Rutas</h2>
                <p class="text-muted mb-4">
                    Completa tus datos para enviar tu solicitud de registro.
                </p>

                <div class="alert alert-light border rounded-3 mb-4">
                    <strong>Importante:</strong> tu cuenta quedará pendiente de aprobación
                    hasta que el administrador la revise.
                </div>

                <!-- Mensaje general de errores -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>¡Atención!</strong> Corrige los errores en el formulario.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.rutas') }}" novalidate>
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">Nombre *</label>
                        <input type="text"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Tu nombre"
                               required
                               autofocus>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Apellido Paterno -->
                    <div class="mb-3">
                        <label for="apaterno" class="form-label fw-semibold">Apellido paterno *</label>
                        <input type="text"
                               id="apaterno"
                               name="apaterno"
                               value="{{ old('apaterno') }}"
                               class="form-control @error('apaterno') is-invalid @enderror"
                               placeholder="Apellido paterno"
                               required>
                        @error('apaterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Apellido Materno -->
                    <div class="mb-3">
                        <label for="amaterno" class="form-label fw-semibold">Apellido materno</label>
                        <input type="text"
                               id="amaterno"
                               name="amaterno"
                               value="{{ old('amaterno') }}"
                               class="form-control @error('amaterno') is-invalid @enderror"
                               placeholder="Apellido materno (opcional)">
                        @error('amaterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-semibold">Teléfono *</label>
                        <input type="tel"
                               id="telefono"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               class="form-control @error('telefono') is-invalid @enderror"
                               placeholder="9611234567"
                               maxlength="10"
                               pattern="\d{10}"
                               inputmode="numeric"
                               required>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Correo -->
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo electrónico *</label>
                        <input type="email"
                               id="correo"
                               name="correo"
                               value="{{ old('correo') }}"
                               class="form-control @error('correo') is-invalid @enderror"
                               placeholder="nombre@correo.com"
                               required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Contraseña *</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña *</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        Enviar solicitud
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection