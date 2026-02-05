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
                </form>

            </div>
        </div>

    </div>
</section>
@endsection
