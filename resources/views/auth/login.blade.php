@extends('layouts.app')

@php
  $hideNavbar = true;
@endphp

@section('content')
<section class="min-vh-100 d-flex align-items-center justify-content-center" style="background:#F7F6EF;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5">

        <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">

          {{-- Volver --}}
          <a href="{{ route('home') }}" class="text-decoration-none text-secondary small">
            ← Volver al inicio
          </a>

          {{-- Título --}}
          <h2 class="mt-3 fw-semibold" style="font-family: Georgia, 'Times New Roman', serif;">
            Iniciar Sesión
          </h2>
          <p class="text-secondary small mb-4">
            Ingresa tus credenciales para acceder
          </p>

          {{-- ERRORES --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- FORM --}}
          <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-semibold">Correo electrónico</label>
              <input
                type="email"
                name="correo"
                value="{{ old('correo') }}"
                class="form-control form-control-lg"
                placeholder="tu@email.com"
                required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Contraseña</label>
              <input
                type="password"
                name="password"
                class="form-control form-control-lg"
                placeholder="••••••••"
                required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-secondary" for="remember">
                  Recordarme
                </label>
              </div>

              <a href="#" class="text-decoration-none text-secondary small">
                ¿Olvidaste tu contraseña?
              </a>
            </div>

            <button type="submit"
                    class="btn w-100 btn-lg text-white"
                    style="background:#064e3b;">
              Iniciar Sesión
            </button>

            {{-- Separador --}}
            <div class="d-flex align-items-center my-4">
              <div class="flex-grow-1 border-top"></div>
              <span class="px-3 small text-secondary text-uppercase" style="letter-spacing:.1em;">
                o continúa con
              </span>
              <div class="flex-grow-1 border-top"></div>
            </div>

            {{-- Google --}}
            <a href="{{ route('google.login') }}"
               class="btn btn-lg w-100 border d-flex align-items-center justify-content-center gap-2">
              <svg width="20" height="20" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.72 1.22 9.23 3.6l6.88-6.88C35.64 2.23 30.21 0 24 0 14.61 0 6.37 5.4 2.36 13.28l8.01 6.21C12.4 13.27 17.7 9.5 24 9.5z"/>
                <path fill="#34A853" d="M46.14 24.5c0-1.64-.15-3.21-.43-4.73H24v9h12.44c-.54 2.9-2.18 5.36-4.64 7.01l7.15 5.56C43.97 37.2 46.14 31.36 46.14 24.5z"/>
                <path fill="#4A90E2" d="M10.37 28.49a14.5 14.5 0 010-8.98l-8.01-6.21A23.94 23.94 0 000 24c0 3.88.93 7.54 2.36 10.7l8.01-6.21z"/>
                <path fill="#FBBC05" d="M24 48c6.21 0 11.64-2.05 15.52-5.56l-7.15-5.56c-2 1.34-4.56 2.12-8.37 2.12-6.3 0-11.6-3.77-13.63-9.99l-8.01 6.21C6.37 42.6 14.61 48 24 48z"/>
              </svg>
              Iniciar sesión con Google
            </a>

          </form>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection