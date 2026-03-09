@extends('layouts.app')

@php $hideNavbar = true; @endphp

@section('content')
<div class="container-fluid min-vh-100">
    <div class="row min-vh-100">

        <!-- LADO IZQUIERDO -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center text-white"
            style="background: linear-gradient(135deg,#064e3b,#065f46,#10b981);">

            <div class="px-5">

                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                        style="width:45px;height:45px;">
                        <span>🌿</span>
                    </div>

                    <span class="fs-4 fw-semibold">Ecoaventura</span>
                </div>

                <h1 class="mt-5 fw-bold">
                    Únete a la aventura
                </h1>

                <p class="mt-3 opacity-75">
                    Crea tu cuenta y descubre experiencias únicas de ecoturismo.
                </p>

            </div>
        </div>


        <!-- FORMULARIO -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">

            <div style="width:100%; max-width:420px;">

                <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                    ← Volver al inicio
                </a>


                <!-- TABS LOGIN / REGISTRO -->
                <div class="btn-group w-100 mt-4">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Iniciar Sesión
                    </a>

                    <button class="btn btn-success active">
                        Registrarse
                    </button>
                </div>


                <h2 class="mt-4 fw-bold">Crear Cuenta</h2>

                <p class="text-muted small">
                    Regístrate como turista y empieza a explorar
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


                <form method="POST" action="{{ route('registro.turista.post') }}" class="mt-3">
                    @csrf


                    <div class="mb-3">
                        <label class="form-label small">Nombre *</label>
                        <input type="text"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               class="form-control"
                               placeholder="Tu nombre"
                               required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label small">Apellido paterno *</label>
                        <input type="text"
                               name="apaterno"
                               value="{{ old('apaterno') }}"
                               class="form-control"
                               placeholder="Apellido paterno"
                               required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label small">Apellido materno</label>
                        <input type="text"
                               name="amaterno"
                               value="{{ old('amaterno') }}"
                               class="form-control"
                               placeholder="Apellido materno (opcional)">
                    </div>


                    <div class="mb-3">
                        <label class="form-label small">Correo electrónico *</label>
                        <input type="email"
                               name="correo"
                               value="{{ old('correo') }}"
                               class="form-control"
                               placeholder="tu@email.com"
                               required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label small">Contraseña *</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label small">Confirmar contraseña *</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>


                    <button type="submit" class="btn btn-success w-100">
                        Crear Cuenta
                    </button>


                    <!-- DIVISOR -->
                    <div class="d-flex align-items-center my-3">
                        <hr class="flex-grow-1">
                        <span class="mx-2 text-muted small">o continúa con</span>
                        <hr class="flex-grow-1">
                    </div>


                    <!-- GOOGLE LOGIN -->
                    <a href="{{ route('google.login') }}"
                       class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">

                        <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                             width="20">

                        Continuar con Google
                    </a>

                </form>

            </div>

        </div>

    </div>
</div>
@endsection