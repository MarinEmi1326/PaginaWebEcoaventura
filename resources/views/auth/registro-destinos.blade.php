@extends('layouts.app')

@php $hideNavbar = true; @endphp

@section('content')
<div class="container-fluid min-vh-100">
    <div class="row min-vh-100">

        <!-- LADO IZQUIERDO -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center text-white"
            style="background: linear-gradient(135deg,#0b3c7d,#0d4ea6,#1e6ed8);">

            <div class="px-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:45px;height:45px;">
                        <span>🏔️</span>
                    </div>
                    <span class="fs-4 fw-semibold">Ecoaventura</span>
                </div>

                <h1 class="mt-5 fw-bold">Administra tus destinos</h1>

                <p class="mt-3 opacity-75">
                    Registra tu destino y llega a miles de turistas.
                </p>

                <div class="alert alert-light mt-4">
                    ⏳ Tu cuenta quedará <strong>pendiente de aprobación</strong>
                    hasta que el administrador la revise.
                </div>
            </div>
        </div>

        <!-- FORMULARIO -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">

            <div style="width:100%;max-width:420px;">

                <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                    ← Volver al inicio
                </a>

                <h2 class="mt-4 fw-bold">Registro — Admin Destinos</h2>
                <p class="text-muted small">
                    Completa tus datos para enviar tu solicitud
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="/registro/destinos" class="mt-3">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small">Nombre *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                            class="form-control"
                            placeholder="Tu nombre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Apellido paterno *</label>
                        <input type="text" name="apaterno" value="{{ old('apaterno') }}"
                            class="form-control"
                            placeholder="Apellido paterno" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Apellido materno</label>
                        <input type="text" name="amaterno" value="{{ old('amaterno') }}"
                            class="form-control"
                            placeholder="Apellido materno (opcional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Teléfono *</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                            class="form-control"
                            maxlength="10"
                            placeholder="9611234567"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Correo electrónico *</label>
                        <input type="email" name="correo" value="{{ old('correo') }}"
                            class="form-control"
                            placeholder="tu@email.com"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Contraseña *</label>
                        <input type="password" name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Confirmar contraseña *</label>
                        <input type="password" name="password_confirmation"
                            class="form-control"
                            placeholder="••••••••"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Enviar Solicitud
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection