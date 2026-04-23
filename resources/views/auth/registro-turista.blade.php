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

                <h2 class="fw-bold mb-2">Registro — Turista</h2>
                <p class="text-muted mb-4">
                    Crea tu cuenta para explorar destinos, comprar paquetes y dejar comentarios.
                </p>

                <div class="alert alert-info border-0 bg-light rounded-3 mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Nota:</strong> Tu cuenta quedará activa de inmediato.
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>¡Atención!</strong> Revisa los campos marcados en rojo.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.turista.post') }}" novalidate>
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="nombre" class="form-label fw-semibold">Nombre(s) *</label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   placeholder="Ej. Juan Carlos"
                                   oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúñÑ\s]/g, '')"
                                   required autofocus>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="apellidos" class="form-label fw-semibold">Apellidos *</label>
                            <input type="text" 
                                   id="apellidos" 
                                   name="apellidos" 
                                   value="{{ old('apellidos') }}" 
                                   class="form-control @error('apellidos') is-invalid @enderror" 
                                   placeholder="Ej. Pérez García"
                                   oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúñÑ\s]/g, '')"
                                   required>
                            @error('apellidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-semibold">Teléfono (10 dígitos) *</label>
                        <input type="tel" 
                               id="telefono" 
                               name="telefono" 
                               value="{{ old('telefono') }}" 
                               class="form-control @error('telefono') is-invalid @enderror" 
                               maxlength="10"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               placeholder="9611234567"
                               required>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4 text-muted">

                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo electrónico *</label>
                        <input type="email" 
                               id="correo" 
                               name="correo" 
                               value="{{ old('correo') }}" 
                               class="form-control @error('correo') is-invalid @enderror" 
                               placeholder="nombre@ejemplo.com"
                               oninput="validarCorreo(this)"
                               required>
                        <div id="correoError" class="text-danger small d-none">
                            Correo no válido
                        </div>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 position-relative">
                            <label for="password" class="form-label fw-semibold">Contraseña *</label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   minlength="8"
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Mín. 8 caracteres"
                                   required>
                            <span class="toggle-password d-none" toggle="#password" style="position:absolute; top:38px; right:15px; cursor:pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4 position-relative">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña *</label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   minlength="8"
                                   class="form-control" 
                                   placeholder="Repite tu contraseña"
                                   required>
                            <span class="toggle-password d-none" toggle="#password_confirmation" style="position:absolute; top:38px; right:15px; cursor:pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                        REGISTRARSE
                    </button>
                    
                    <p class="text-center mt-4 text-muted small">
                        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-success fw-semibold">Inicia sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Ojito dinámico
    document.querySelectorAll("input[type='password']").forEach(input => {
        const toggle = input.parentElement.querySelector(".toggle-password");

        input.addEventListener("input", () => {
            if (input.value.length > 0) {
                toggle.classList.remove("d-none");
            } else {
                toggle.classList.add("d-none");
                input.type = "password";
                toggle.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    });

    document.querySelectorAll(".toggle-password").forEach(toggle => {
        toggle.addEventListener("click", function () {
            const input = document.querySelector(this.getAttribute("toggle"));

            if (input.type === "password") {
                input.type = "text";
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = "password";
                this.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    });

});

// Validación correo en tiempo real
function validarCorreo(input) {
    const error = document.getElementById("correoError");

    if (!input.value.includes("@")) {
        input.classList.add("is-invalid");
        error.classList.remove("d-none");
        input.setCustomValidity("Correo no válido");
    } else {
        input.classList.remove("is-invalid");
        error.classList.add("d-none");
        input.setCustomValidity("");
    }
}
</script>

@endsection