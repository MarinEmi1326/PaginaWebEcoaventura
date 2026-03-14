@extends('layouts.app')

@php $hideNavbar = true; @endphp

@section('content')
<div class="container-fluid min-vh-100">
    <div class="row min-vh-100">

        <!-- LADO IZQUIERDO (solo en pantallas grandes) -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center text-white"
             style="background: linear-gradient(135deg, #064e3b, #065f46, #10b981);">

            <div class="px-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                         style="width:45px; height:45px;">
                        <img src="{{ asset('img/ecoaventura-logo.png') }}" alt="Ecoaventura" style="width:26px; height:26px;">
                    </div>
                    <span class="fs-4 fw-semibold">Ecoaventura</span>
                </div>

                <h1 class="mt-5 fw-bold">Únete a la aventura</h1>
                <p class="mt-3 opacity-75">
                    Crea tu cuenta y descubre experiencias únicas de ecoturismo.
                </p>
            </div>
        </div>

        <!-- FORMULARIO -->
        <div class="col-lg-6 col-12 d-flex align-items-center justify-content-center bg-light py-5">

            <div style="width:100%; max-width:420px; padding: 0 15px;">

                <a href="{{ route('home') }}" class="text-decoration-none text-muted d-block mb-4">
                    ← Volver al inicio
                </a>

                <!-- TABS -->
                <div class="btn-group w-100 mb-4">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Iniciar Sesión
                    </a>
                    <button class="btn btn-success active" disabled>
                        Registrarse
                    </button>
                </div>

                <h2 class="fw-bold mb-2">Crear Cuenta como Turista</h2>
                <p class="text-muted small mb-4">
                    Regístrate y empieza a explorar experiencias únicas
                </p>

                <!-- Mensaje general de errores (opcional, se puede quitar si usas solo por campo) -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>¡Atención!</strong> Corrige los errores en el formulario.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.turista.post') }}" novalidate class="mt-3">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label class="form-label small">Nombre *</label>
                        <input type="text"
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
                        <label class="form-label small">Apellido paterno *</label>
                        <input type="text"
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
                        <label class="form-label small">Apellido materno</label>
                        <input type="text"
                               name="amaterno"
                               value="{{ old('amaterno') }}"
                               class="form-control @error('amaterno') is-invalid @enderror"
                               placeholder="Apellido materno (opcional)">
                        @error('amaterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teléfono (activado y mejorado) -->
                    <div class="mb-3">
                        <label class="form-label small">Teléfono *</label>
                        <input type="tel"
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
                        <label class="form-label small">Correo electrónico *</label>
                        <input type="email"
                               name="correo"
                               value="{{ old('correo') }}"
                               class="form-control @error('correo') is-invalid @enderror"
                               placeholder="tu@email.com"
                               required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label class="form-label small">Contraseña *</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label class="form-label small">Confirmar contraseña *</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        Crear Cuenta
                    </button>

                    <!-- Divisor -->
                    <div class="d-flex align-items-center my-4">
                        <hr class="flex-grow-1">
                        <span class="mx-3 text-muted small">o continúa con</span>
                        <hr class="flex-grow-1">
                    </div>

                    <!-- Google -->
                    <a href="{{ route('google.login') }}"
                       class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" alt="Google">
                        Continuar con Google
                    </a>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection