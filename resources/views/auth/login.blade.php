@extends('layouts.app')

@php
    // 👇 Oculta el navbar SOLO en login
    $hideNavbar = true;
@endphp

@section('content')
<section class="min-h-screen bg-[#F7F6EF]">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        {{-- IZQUIERDA (VERDE) --}}
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-700 to-teal-400"></div>

            {{-- brillos --}}
            <div class="absolute -bottom-40 -right-40 h-[520px] w-[520px] rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -top-40 -left-40 h-[520px] w-[520px] rounded-full bg-black/10 blur-3xl"></div>

            <div class="relative h-full px-10 lg:px-16 py-14 flex flex-col justify-center text-white">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-white/15 ring-1 ring-white/20 flex items-center justify-center backdrop-blur">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 20c-7 0-9-9-9-9S6 4 13 4c6 0 9 4 9 9 0 0-4 7-11 7Z"/>
                            <path d="M8 13c3 0 8-3 10-7"/>
                        </svg>
                    </div>
                    <span class="text-xl font-semibold tracking-tight">Ecoaventura</span>
                </div>

                <h1 class="mt-14 text-4xl lg:text-5xl font-serif font-semibold">
                    Bienvenido de vuelta
                </h1>

                <p class="mt-6 text-white/80 max-w-md leading-relaxed">
                    Accede a tu cuenta y continúa explorando destinos increíbles.
                </p>
            </div>
        </div>

        {{-- DERECHA (FORM) --}}
        <div class="px-6 sm:px-10 lg:px-16 py-12 flex items-center">
            <div class="w-full max-w-md mx-auto">

                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
                    <span>←</span> Volver al inicio
                </a>

                {{-- TABS FUNCIONALES --}}
                <div class="mt-8 bg-slate-200/70 rounded-2xl p-1 flex items-center">
                    <a href="{{ route('login') }}"
                       class="flex-1 rounded-xl bg-white/70 ring-1 ring-slate-900/10 px-4 py-2 text-sm font-medium text-slate-900 text-center">
                        Iniciar Sesión
                    </a>

                    <a href="{{ route('register') }}"
                       class="flex-1 text-center px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                        Registrarse
                    </a>
                </div>

                <h2 class="mt-10 text-2xl font-serif font-semibold text-slate-900">
                    Iniciar Sesión
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Ingresa tus credenciales para acceder
                </p>

                {{-- ERRORES --}}
                @if ($errors->any())
                    <div class="mt-6 rounded-xl bg-red-50 text-red-700 ring-1 ring-red-200 p-4 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORMULARIO --}}
                <form class="mt-8 space-y-5" method="POST" action="{{ route('login.post') }}">
                    @csrf

                    {{-- correo --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Correo electrónico
                        </label>
                        <input
                            type="email"
                            name="correo"
                            value="{{ old('correo') }}"
                            placeholder="tu@email.com"
                            class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-sm outline-none
                                   focus:ring-2 focus:ring-emerald-300/60 focus:border-emerald-300"
                            required
                        />
                    </div>

                    {{-- password --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Contraseña
                        </label>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-slate-200 bg-white/70 px-4 py-3 text-sm outline-none
                                   focus:ring-2 focus:ring-emerald-300/60 focus:border-emerald-300"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-slate-600">
                            <input type="checkbox" name="remember"
                                   class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-300">
                            Recordarme
                        </label>

                        <a href="#"
                           class="text-slate-600 hover:text-slate-900">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-emerald-900 py-3 text-white font-semibold
                               hover:bg-emerald-950 transition">
                        Iniciar Sesión
                    </button>
                    {{-- Separador --}}
<div class="flex items-center my-6">
    <div class="flex-1 h-px bg-slate-200"></div>
    <span class="px-3 text-xs text-slate-400 uppercase tracking-wider">o continúa con</span>
    <div class="flex-1 h-px bg-slate-200"></div>
</div>

{{-- Botón Google elegante --}}
<a href="{{ route('google.login') }}"
   class="w-full flex items-center justify-center gap-3
          bg-white border border-slate-200 py-3 rounded-xl
          text-slate-700 text-sm font-medium
          hover:bg-slate-50 hover:shadow-sm
          transition duration-200">

    <svg class="w-5 h-5" viewBox="0 0 48 48">
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
</section>
@endsection
