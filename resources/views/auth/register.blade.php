@extends('layouts.app')

@php
    // 👇 Esto oculta el navbar SOLO en esta vista (registro)
    $hideNavbar = true;
@endphp

@section('content')
<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- LADO IZQUIERDO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-600 text-white">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-[-120px] right-[-80px] w-[520px] h-[520px] rounded-full bg-white/10 blur-3xl"></div>
        </div>

        <div class="relative h-full flex items-center px-10 lg:px-16 py-16">
            <div class="max-w-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-full bg-white/10 ring-1 ring-white/15 flex items-center justify-center">
                        <span class="text-xl">🌿</span>
                    </div>
                    <span class="text-xl font-semibold">Ecoaventura</span>
                </div>

                <h1 class="mt-10 text-4xl font-serif font-semibold leading-tight">
                    Únete a la aventura
                </h1>

                <p class="mt-4 text-white/75">
                    Crea tu cuenta y descubre experiencias únicas de ecoturismo.
                </p>
            </div>
        </div>
    </section>

    {{-- LADO DERECHO --}}
    <section class="bg-[#F7F6EF] flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-slate-800 inline-flex items-center gap-2">
                ← Volver al inicio
            </a>

            {{-- Tabs --}}
            <div class="mt-6 p-1 rounded-xl bg-slate-200/70 flex">
                <a href="{{ route('login') }}" 
                   class="w-1/2 text-center text-sm py-2 rounded-lg text-slate-600 hover:text-slate-900 transition">
                    Iniciar Sesión
                </a>
                <div class="w-1/2 text-center text-sm py-2 rounded-lg bg-white shadow-sm text-slate-900 font-medium">
                    Registrarse
                </div>
            </div>

            <h2 class="mt-8 text-2xl font-serif font-semibold text-slate-900">Crear Cuenta</h2>
            <p class="mt-1 text-sm text-slate-500">Selecciona tu tipo de cuenta y completa el registro</p>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc ml-5 space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="mt-6 space-y-4">
                @csrf

                <input type="hidden" name="rol" id="rol" value="{{ old('rol', 'turista') }}">

                {{-- Selector rol --}}
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" data-rol="turista" class="rol-btn rounded-xl border px-3 py-3 text-sm flex flex-col items-center gap-2 bg-emerald-50 border-emerald-700 text-emerald-900">
                        <span></span> Turista
                    </button>

                    <button type="button" data-rol="hotelero" class="rol-btn rounded-xl border px-3 py-3 text-sm flex flex-col items-center gap-2 bg-white border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                        <span></span> Hotelero
                    </button>

                    <button type="button" data-rol="restaurantero" class="rol-btn rounded-xl border px-3 py-3 text-sm flex flex-col items-center gap-2 bg-white border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                        <span></span> Restaurantero
                    </button>
                </div>

                {{-- Nombre --}}
                <div>
                    <label class="text-xs text-slate-600">Nombre</label>
                    <input name="nombre" value="{{ old('nombre') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Tu nombre">
                </div>

                {{-- Apaterno --}}
                <div>
                    <label class="text-xs text-slate-600">Apellido paterno</label>
                    <input name="apaterno" value="{{ old('apaterno') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Apellido paterno">
                </div>

                {{-- Amaterno --}}
                <div>
                    <label class="text-xs text-slate-600">Apellido materno (opcional)</label>
                    <input name="amaterno" value="{{ old('amaterno') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Apellido materno">
                </div>

                {{-- Correo --}}
                <div>
                    <label class="text-xs text-slate-600">Correo electrónico</label>
                    <input name="correo" value="{{ old('correo') }}" type="email"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="tu@email.com">
                </div>

                {{-- Teléfono --}}
                <div id="telefono-container" style="display:none;">
                    <label class="text-xs text-slate-600">Teléfono (opcional)</label>
                    <input name="telefono" value="{{ old('telefono') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="+52 123 456 7890">
                </div>

                {{-- Nombre del hotel --}}
                <div id="hotel-container" style="display:none;">
                    <label class="text-xs text-slate-600">Nombre del hotel</label>
                    <input name="nombre_hotel" value="{{ old('nombre_hotel') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Nombre del hotel">
                </div>

                {{-- Dirección del hotel --}}
                <div id="direccion-container" style="display:none;">
                    <label class="text-xs text-slate-600">Dirección del hotel</label>
                    <input name="direccion_hotel" value="{{ old('direccion_hotel') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Dirección del hotel">
                </div>

                {{-- Nombre del restaurante --}}
                <div id="restaurante-container" style="display:none;">
                    <label class="text-xs text-slate-600">Nombre del restaurante</label>
                    <input name="nombre_restaurante" value="{{ old('nombre_restaurante') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Nombre del restaurante">
                </div>

                {{-- Dirección del restaurante --}}
                <div id="direccion-restaurante-container" style="display:none;">
                    <label class="text-xs text-slate-600">Dirección del restaurante</label>
                    <input name="direccion_restaurante" value="{{ old('direccion_restaurante') }}" type="text"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Dirección del restaurante">
                </div>

                {{-- Contraseña --}}
                <div>
                    <label class="text-xs text-slate-600">Contraseña</label>
                    <input name="password" type="password"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="••••••••">
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <label class="text-xs text-slate-600">Confirmar contraseña</label>
                    <input name="password_confirmation" type="password"
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="••••••••">
                </div>

                <label class="flex items-center gap-2 text-xs text-slate-600">
                    <input type="checkbox" name="terms"
                           class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-200"
                           {{ old('terms') ? 'checked' : '' }}>
                    Acepto los términos y condiciones y la política de privacidad
                </label>

                <button type="submit"
                    class="w-full rounded-xl bg-emerald-900 text-white py-3 text-sm font-semibold hover:bg-emerald-950 transition">
                    Crear Cuenta
                </button>
            </form>
        </div>
    </section>
</div>

<script>
    const inputRol = document.getElementById('rol');
    const buttons = document.querySelectorAll('.rol-btn');

    function paintActive(role){
        buttons.forEach(btn => {
            const isActive = btn.dataset.rol === role;
            btn.classList.toggle('bg-emerald-50', isActive);
            btn.classList.toggle('border-emerald-700', isActive);
            btn.classList.toggle('text-emerald-900', isActive);

            btn.classList.toggle('bg-white', !isActive);
            btn.classList.toggle('border-slate-200', !isActive);
            btn.classList.toggle('text-slate-700', !isActive);
        });

        // Mostrar campos según el rol
        if (role === 'hotelero' || role === 'restaurantero') {
            document.getElementById('telefono-container').style.display = 'block'; // Mostrar teléfono
        } else {
            document.getElementById('telefono-container').style.display = 'none'; // Ocultar teléfono
        }

        if (role === 'hotelero') {
            document.getElementById('hotel-container').style.display = 'block'; // Mostrar nombre de hotel
            document.getElementById('direccion-container').style.display = 'block'; // Mostrar dirección del hotel
            document.getElementById('restaurante-container').style.display = 'none'; // Ocultar restaurante
            document.getElementById('direccion-restaurante-container').style.display = 'none'; // Ocultar dirección del restaurante
        } else if (role === 'restaurantero') {
            document.getElementById('restaurante-container').style.display = 'block'; // Mostrar nombre del restaurante
            document.getElementById('direccion-restaurante-container').style.display = 'block'; // Mostrar dirección del restaurante
            document.getElementById('hotel-container').style.display = 'none'; // Ocultar nombre del hotel
            document.getElementById('direccion-container').style.display = 'none'; // Ocultar dirección del hotel
        } else {
            document.getElementById('hotel-container').style.display = 'none'; // Ocultar nombre de hotel
            document.getElementById('direccion-container').style.display = 'none'; // Ocultar dirección del hotel
            document.getElementById('restaurante-container').style.display = 'none'; // Ocultar nombre del restaurante
            document.getElementById('direccion-restaurante-container').style.display = 'none'; // Ocultar dirección del restaurante
        }
    }

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            inputRol.value = btn.dataset.rol;
            paintActive(inputRol.value);
        });
    });

    paintActive(inputRol.value || 'turista'); // Establecer valor inicial
</script>
@endsection
