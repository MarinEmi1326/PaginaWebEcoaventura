@extends('layouts.app')

@section('content')
    <section class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">

                    {{-- Icono arriba --}}
                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width:64px; height:64px; background:#e2ece9;">

                            <img src="{{ asset('img/ecoaventura-logo.png') }}" alt="Logo" style="width:32px; height:32px;">

                        </div>
                        <h2 class="fw-bold" style="font-family: Georgia, 'Times New Roman', serif;">Iniciar Sesión</h2>
                        <p class="text-muted small">Ingresa tus credenciales para acceder</p>
                    </div>

                    {{-- Card formulario --}}
                    <div class="rounded-4 p-4 p-md-5" style="background:#f1f5f2;">

                        {{-- ERRORES --}}
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger rounded-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- FORM --}}
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            {{-- CORREO --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Correo electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo') }}"
                                    class="form-control form-control-lg bg-white border-0 rounded-3
                                    {{ session('error_tipo') == 'correo' ? 'is-invalid border border-danger' : '' }}"
                                    placeholder="usuario@ecoaventura.mx"
                                    required
                                    oninput="validarCorreo(this)">

                                @if(session('error_tipo') == 'correo')
                                    <div class="text-danger small mt-1">
                                        Correo no registrado
                                    </div>
                                @endif

                                <small id="correoError" class="text-danger d-none">Correo inválido</small>
                            </div>

                            {{-- CONTRASEÑA --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Contraseña</label>

                                <div class="position-relative">
                                    <input type="password" name="password" id="password"
                                        class="form-control form-control-lg bg-white border-0 rounded-3 pe-5
                                        {{ session('error_tipo') == 'password' ? 'is-invalid border border-danger' : '' }}"
                                        placeholder="••••••••"
                                        required
                                        oninput="toggleEyeVisibility()">

                                    <button type="button" id="btnTogglePassword"
                                        class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent d-none"
                                        onclick="togglePassword()">
                                        <i id="iconPassword" class="bi bi-eye"></i>
                                    </button>
                                </div>

                                @if(session('error_tipo') == 'password')
                                    <div class="text-danger small mt-1">
                                        Contraseña incorrecta
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small text-muted" for="remember">Recordarme</label>
                                </div>
                                <a href="#" class="text-decoration-none small text-muted">¿Olvidaste tu
                                    contraseña?</a>
                            </div>

                            <button type="submit" class="btn w-100 btn-lg text-white rounded-3 mb-3"
                                style="background:#1F6B4B;">
                                Iniciar sesión
                            </button>

                            <p class="text-center small mt-3">
                                ¿No tienes cuenta?
                                <a href="{{ route('registro.turista') }}" class="text-decoration-none fw-semibold">
                                    Regístrate
                                </a>
                            </p>

                            {{-- GOOGLE (100% intacto como lo tenías) --}}
                            <a href="{{ route('google.login') }}"
                                class="btn btn-lg w-100 border d-flex align-items-center justify-content-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 48 48">
                                    <path fill="#EA4335"
                                        d="M24 9.5c3.54 0 6.72 1.22 9.23 3.6l6.88-6.88C35.64 2.23 30.21 0 24 0 14.61 0 6.37 5.4 2.36 13.28l8.01 6.21C12.4 13.27 17.7 9.5 24 9.5z" />
                                    <path fill="#34A853"
                                        d="M46.14 24.5c0-1.64-.15-3.21-.43-4.73H24v9h12.44c-.54 2.9-2.18 5.36-4.64 7.01l7.15 5.56C43.97 37.2 46.14 31.36 46.14 24.5z" />
                                    <path fill="#4A90E2"
                                        d="M10.37 28.49a14.5 14.5 0 010-8.98l-8.01-6.21A23.94 23.94 0 000 24c0 3.88.93 7.54 2.36 10.7l8.01-6.21z" />
                                    <path fill="#FBBC05"
                                        d="M24 48c6.21 0 11.64-2.05 15.52-5.56l-7.15-5.56c-2 1.34-4.56 2.12-8.37 2.12-6.3 0-11.6-3.77-13.63-9.99l-8.01 6.21C6.37 42.6 14.61 48 24 48z" />
                                </svg>
                                Iniciar sesión con Google
                            </a>

                            <p class="text-center text-muted small mb-0">
                                Ambiente de demostración. Ingresa tus credenciales para acceder.
                            </p>

                        </form>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                            ← Volver al inicio
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- SCRIPT --}}
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("iconPassword");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }

        function toggleEyeVisibility() {
            const input = document.getElementById("password");
            const btn = document.getElementById("btnTogglePassword");

            if (input.value.length > 0) {
                btn.classList.remove("d-none");
            } else {
                btn.classList.add("d-none");
            }
        }

        function validarCorreo(input) {
            const error = document.getElementById("correoError");
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regex.test(input.value)) {
                error.classList.remove("d-none");
            } else {
                error.classList.add("d-none");
            }
        }
    </script>
@endsection