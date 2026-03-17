@extends('layouts.app')

@php $hideNavbar = true; @endphp

@section('content')

<div class="container py-5" style="max-width:700px;">

    <a href="{{ route('home') }}" class="text-decoration-none text-muted d-block mb-4">
        ← Volver al inicio
    </a>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">

            <h2 class="fw-bold mb-2">
                Crear Cuenta como Turista
            </h2>

            <p class="text-muted mb-4">
                Regístrate y empieza a explorar experiencias únicas
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    Corrige los errores en el formulario.
                </div>
            @endif

            <form method="POST" action="{{ route('registro.turista.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text"
                           name="nombre"
                           value="{{ old('nombre') }}"
                           class="form-control @error('nombre') is-invalid @enderror"
                           placeholder="Tu nombre">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido paterno *</label>
                    <input type="text"
                           name="apaterno"
                           value="{{ old('apaterno') }}"
                           class="form-control @error('apaterno') is-invalid @enderror"
                           placeholder="Apellido paterno">
                    @error('apaterno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido materno</label>
                    <input type="text"
                           name="amaterno"
                           value="{{ old('amaterno') }}"
                           class="form-control"
                           placeholder="Apellido materno (opcional)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono *</label>
                    <input type="tel"
                           name="telefono"
                           value="{{ old('telefono') }}"
                           class="form-control"
                           placeholder="9611234567">
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico *</label>
                    <input type="email"
                           name="correo"
                           value="{{ old('correo') }}"
                           class="form-control"
                           placeholder="tu@email.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña *</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="••••••••">
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmar contraseña *</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                    Crear Cuenta
                </button>

                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1">
                    <span class="mx-3 text-muted small">o continúa con</span>
                    <hr class="flex-grow-1">
                </div>

                <a href="{{ route('google.login') }}"
                   class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20">
                    Continuar con Google
                </a>

            </form>

        </div>
    </div>

</div>

@endsection