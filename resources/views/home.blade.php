@extends('layouts.app')

@section('content')

{{-- HERO --}}

<section class="relative h-screen bg-cover bg-center"
    style="background-image: url('{{ asset('img/tonina.jpeg') }}');">

    {{-- OVERLAY VERDE TRANSPARENTE (SUAVE) --}}
    <div class="absolute inset-0 bg-gradient-to-r
                from-emerald-900/45
                via-emerald-800/30
                to-emerald-700/20">
    </div>

    <div class="relative z-10 h-full flex items-center px-16 max-w-5xl text-white">
        <div>

            {{-- BADGE BLANCO TRANSPARENTE --}}
            <span class="inline-flex items-center
                         bg-white/20 backdrop-blur-md
                         px-6 py-2 rounded-full
                         text-sm font-medium
                         text-white
                         ring-1 ring-white/40
                         mb-6">
                Turismo sostenible y responsable
            </span>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                Descubre la magia de la naturaleza
            </h1>

            {{-- TEXTO --}}
            <p class="mt-6 text-lg text-white/90 max-w-2xl">
                Vive experiencias únicas de ecoturismo. Explora destinos extraordinarios,
                conecta con la naturaleza y crea recuerdos inolvidables con aventuras sostenibles.
            </p>

            {{-- BOTONES (LOGIN / REGISTER / LOGOUT) --}}
            <div class="mt-10 flex gap-4 flex-wrap">

                @guest
                    <a href="{{ route('login') }}"
                       class="bg-white/90 text-emerald-900 px-7 py-3 rounded-xl font-medium hover:bg-white transition">
                        Explorar Destinos →
                    </a>

                    <a href="{{ route('register') }}"
                       class="border border-white/70 px-7 py-3 rounded-xl hover:bg-white/10 transition">
                        Únete ahora
                    </a>
                @endguest

                @auth
                    <a href="{{ route('home') }}"
                       class="bg-white/90 text-emerald-900 px-7 py-3 rounded-xl font-medium hover:bg-white transition">
                         Explorar Destinos→
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="border border-white/70 px-7 py-3 rounded-xl hover:bg-white/10 transition">
                            Únete ahora
                        </button>
                    </form>
                @endauth

            </div>

            {{-- ESTADÍSTICAS CON ICONOS VERDES --}}
            <div class="flex flex-wrap gap-12 mt-16">

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-400/15 ring-1 ring-emerald-300/40 backdrop-blur-md flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold leading-none">50+</h3>
                        <p class="text-sm text-white/80 mt-1">Destinos</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-400/15 ring-1 ring-emerald-300/40 backdrop-blur-md flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M14.5 9.5 13 13l-3.5 1.5L11 11l3.5-1.5Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold leading-none">100+</h3>
                        <p class="text-sm text-white/80 mt-1">Experiencias</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-400/15 ring-1 ring-emerald-300/40 backdrop-blur-md flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold leading-none">10K+</h3>
                        <p class="text-sm text-white/80 mt-1">Viajeros felices</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
{{-- DESTINOS DESTACADOS --}}
<section class="py-20 md:py-24 bg-[#F7F6EF]">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center max-w-3xl mx-auto">
            <p class="text-xs tracking-[0.25em] font-semibold text-emerald-700/80 uppercase">
                Destinos destacados
            </p>

            <h2 class="mt-4 text-4xl md:text-5xl font-serif font-semibold text-slate-900">
                Explora lugares extraordinarios
            </h2>

            <p class="mt-4 text-slate-600 leading-relaxed">
                Descubre destinos únicos que combinan aventura, naturaleza y sostenibilidad.<br>
                Cada lugar es una experiencia inolvidable.
            </p>
        </div>

        <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- Card 1 --}}
           {{-- Card 1 --}}
<div class="relative overflow-hidden rounded-3xl shadow-sm bg-black group">
    <img
        src="{{ asset('img/ecoturisticos/miramar-1.png') }}"
        class="h-[420px] w-full object-cover opacity-95
               transition-transform duration-700 ease-out
               group-hover:scale-110"
        alt="Miramar"
    >

    <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full text-xs font-medium
                 bg-emerald-700/85 text-white ring-1 ring-white/15 backdrop-blur z-10">
        Ecoturístico
    </span>

    <div class="absolute inset-x-0 bottom-0 p-6 text-white z-10"
         style="background: linear-gradient(to top, rgba(10,20,18,0.85), rgba(10,20,18,0.05));">

        <div class="flex items-center gap-2 text-white/80 text-sm">
            <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                <circle cx="12" cy="10" r="2.5"/>
            </svg>
            Sierra Azul
        </div>

        <h3 class="mt-2 text-2xl font-serif font-semibold">
            Miramar
        </h3>

        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17.3l-5.4 3 1-6.1-4.4-4.3 6.1-.9L12 3.5l2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-3Z"/>
                </svg>
                <span class="font-semibold">4.9</span>
            </div>

            <a href="#" class="text-sm text-white/90 hover:text-white transition inline-flex items-center gap-2">
                Ver más <span>→</span>
            </a>
        </div>
    </div>
</div>

{{-- Card 2 --}}
<div class="relative overflow-hidden rounded-3xl shadow-sm bg-black group">
    <img
        src="{{ asset('img/balnearios/encanto-1.png') }}"
        class="h-[420px] w-full object-cover opacity-95
               transition-transform duration-700 ease-out
               group-hover:scale-110"
        alt="Playa Cristalina"
    >

    <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full text-xs font-medium
                 bg-emerald-700/85 text-white ring-1 ring-white/15 backdrop-blur z-10">
        Balneario
    </span>

    <div class="absolute inset-x-0 bottom-0 p-6 text-white z-10"
         style="background: linear-gradient(to top, rgba(10,20,18,0.85), rgba(10,20,18,0.05));">

        <div class="flex items-center gap-2 text-white/80 text-sm">
            <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                <circle cx="12" cy="10" r="2.5"/>
            </svg>
            Costa Azul
        </div>

        <h3 class="mt-2 text-2xl font-serif font-semibold">
            Playa Cristalina
        </h3>

        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17.3l-5.4 3 1-6.1-4.4-4.3 6.1-.9L12 3.5l2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-3Z"/>
                </svg>
                <span class="font-semibold">4.8</span>
            </div>

            <a href="#" class="text-sm text-white/90 hover:text-white transition inline-flex items-center gap-2">
                Ver más <span>→</span>
            </a>
        </div>
    </div>
</div>

{{-- Card 3 --}}
<div class="relative overflow-hidden rounded-3xl shadow-sm bg-black group">
    <img
        src="{{ asset('img/turisticos/mirador-1.png') }}"
        class="h-[420px] w-full object-cover opacity-95
               transition-transform duration-700 ease-out
               group-hover:scale-110"
        alt="Ruinas Ancestrales"
    >

    <span class="absolute top-4 left-4 px-4 py-1.5 rounded-full text-xs font-medium
                 bg-emerald-700/85 text-white ring-1 ring-white/15 backdrop-blur z-10">
        Turístico
    </span>

    <div class="absolute inset-x-0 bottom-0 p-6 text-white z-10"
         style="background: linear-gradient(to top, rgba(10,20,18,0.85), rgba(10,20,18,0.05));">

        <div class="flex items-center gap-2 text-white/80 text-sm">
            <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                <circle cx="12" cy="10" r="2.5"/>
            </svg>
            Valle Sagrado
        </div>

        <h3 class="mt-2 text-2xl font-serif font-semibold">
            Ruinas Ancestrales
        </h3>

        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17.3l-5.4 3 1-6.1-4.4-4.3 6.1-.9L12 3.5l2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-3Z"/>
                </svg>
                <span class="font-semibold">4.7</span>
            </div>

            <a href="#" class="text-sm text-white/90 hover:text-white transition inline-flex items-center gap-2">
                Ver más <span>→</span>
            </a>
        </div>
    </div>
</div>


        </div>

        <div class="mt-14 flex justify-center">
            <a href="#"
               class="inline-flex items-center gap-3 rounded-xl px-6 py-3
                      border border-emerald-800/40 text-emerald-900
                      bg-white/40 backdrop-blur
                      hover:bg-white/60 transition">
                Ver todos los destinos <span>→</span>
            </a>
        </div>

    </div>
</section>

{{-- NUESTROS SERVICIOS --}}
<section class="py-20 md:py-24 bg-[#F7F6EF]">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center max-w-3xl mx-auto">
            <p class="text-xs tracking-[0.25em] font-semibold text-emerald-700/80 uppercase">
                Nuestros servicios
            </p>

            <h2 class="mt-4 text-4xl md:text-5xl font-serif font-semibold text-slate-900">
                Todo para tu aventura
            </h2>

            <p class="mt-4 text-slate-600 leading-relaxed">
                Ofrecemos servicios de calidad para que tu experiencia sea completa. Hospedaje y<br>
                gastronomía de primera en armonía con el entorno.
            </p>
        </div>

        <div class="mt-14 grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- Servicio 1 --}}
        {{-- Servicio 1 --}}
<div class="bg-white/70 backdrop-blur rounded-3xl shadow-sm overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2">

        {{-- IMAGEN con zoom --}}
        <div class="relative overflow-hidden group h-[260px] md:h-full md:min-h-[420px]">
            <img
                src="{{ asset('img/Hoteles/ex-hacienda-1.png') }}"
                alt="Hospedaje ecológico"
                class="w-full h-full object-cover
                       transition-transform duration-500 ease-out
                       group-hover:scale-110"
            >

            <span class="absolute top-4 left-4 h-12 w-12 rounded-2xl bg-emerald-800 flex items-center justify-center ring-1 ring-white/20 z-10">
                <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 11l9-7 9 7"/>
                    <path d="M9 22V12h6v10"/>
                </svg>
            </span>
        </div>

        {{-- TEXTO --}}
        <div class="p-7">
            <h3 class="text-xl md:text-2xl font-serif font-semibold text-slate-900">
                Hospedaje Ecológico
            </h3>

            <p class="mt-3 text-slate-600 leading-relaxed">
                Eco-lodges y hoteles boutique en armonía con la naturaleza.
                Alojamientos únicos con vistas impresionantes y servicios de primera.
            </p>

            <ul class="mt-6 space-y-3 text-sm text-slate-700">
                <li class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13a10 10 0 0 1 14 0"/>
                            <path d="M8.5 16.5a5 5 0 0 1 7 0"/>
                            <path d="M12 20h.01"/>
                        </svg>
                    </span>
                    WiFi gratis
                </li>

                <li class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7h13l2 3h3v4h-2"/>
                            <path d="M5 17a2 2 0 1 0 4 0"/>
                            <path d="M15 17a2 2 0 1 0 4 0"/>
                        </svg>
                    </span>
                    Transporte incluido
                </li>

                <li class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4Z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </span>
                    Reserva segura
                </li>
            </ul>

            <a href="#"
               class="mt-7 inline-flex items-center gap-3 rounded-xl px-6 py-3
                      bg-emerald-800 text-white font-semibold text-sm
                      hover:bg-emerald-900 transition">
                Explorar <span>→</span>
            </a>
        </div>
    </div>
</div>

{{-- Servicio 2 --}}
<div class="bg-white/70 backdrop-blur rounded-3xl shadow-sm overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2">

        {{-- IMAGEN con zoom --}}
        <div class="relative overflow-hidden group h-[260px] md:h-full md:min-h-[420px]">
            <img
                src="{{ asset('img/Restaurantes/espresso-1.png') }}"
                alt="Gastronomía local"
                class="w-full h-full object-cover
                       transition-transform duration-500 ease-out
                       group-hover:scale-110"
            >

            <span class="absolute top-4 left-4 h-12 w-12 rounded-2xl bg-emerald-800 flex items-center justify-center ring-1 ring-white/20 z-10">
                <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 3h2l2 7-2 7H4l2-7-2-7Z"/>
                    <path d="M10 3v8a2 2 0 0 0 4 0V3"/>
                    <path d="M18 3v18"/>
                </svg>
            </span>
        </div>

        {{-- TEXTO --}}
        <div class="p-7">
            <h3 class="text-xl md:text-2xl font-serif font-semibold text-slate-900">
                Gastronomía Local
            </h3>

            <p class="mt-3 text-slate-600 leading-relaxed">
                Restaurantes con cocina tradicional y gourmet.
                Sabores auténticos con ingredientes locales y orgánicos de la región.
            </p>

            <ul class="mt-6 space-y-3 text-sm text-slate-700">
                <li class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 7V3"/>
                            <path d="M16 7V3"/>
                            <path d="M4 11h16"/>
                            <path d="M6 5h12a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                        </svg>
                    </span>
                    Reservaciones
                </li>

                <li class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19h16"/>
                            <path d="M6 17V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9"/>
                            <path d="M8 10h8"/>
                        </svg>
                    </span>
                    Menú orgánico
                </li>

                <li class="flex items-center gap-3">
                    <span class="h-7 w-7 rounded-full bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-200">
                        <svg class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8a4 4 0 1 0 0 8"/>
                            <path d="M16 12h6"/>
                            <path d="M18 10l2 2-2 2"/>
                        </svg>
                    </span>
                    Atención 24/7
                </li>
            </ul>

            <a href="#"
               class="mt-7 inline-flex items-center gap-3 rounded-xl px-6 py-3
                      bg-emerald-800 text-white font-semibold text-sm
                      hover:bg-emerald-900 transition">
                Explorar <span>→</span>
            </a>
        </div>
    </div>
</div>


    </div>
</section>

{{-- CTA / ÚNETE A NOSOTROS --}}
<section class="py-20 md:py-24 bg-gradient-to-r from-emerald-900/80 via-emerald-800/70 to-emerald-600/60">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <div class="text-white">
                <p class="text-xs tracking-[0.25em] font-semibold text-white/70 uppercase">
                    Únete a nosotros
                </p>

                <h2 class="mt-4 text-4xl md:text-5xl font-serif font-semibold leading-tight">
                    Comienza tu próxima <span class="text-emerald-200">ecoaventura</span> hoy
                </h2>

                <p class="mt-5 text-white/80 leading-relaxed max-w-xl">
                    Regístrate gratis y accede a ofertas exclusivas, guarda tus destinos favoritos
                    y reserva experiencias increíbles con solo un clic.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-3 rounded-xl px-6 py-3
                              bg-white/90 text-emerald-900 font-semibold text-sm
                              hover:bg-white transition">
                        Crear cuenta gratis <span>→</span>
                    </a>

                    <a href="#"
                       class="inline-flex items-center gap-3 rounded-xl px-6 py-3
                              border border-white/35 text-white font-semibold text-sm
                              bg-white/10 backdrop-blur hover:bg-white/15 transition">
                        Ver destinos <span>→</span>
                    </a>
                </div>
            </div>

            <div class="space-y-6">

                <div class="rounded-2xl bg-white/10 backdrop-blur-md ring-1 ring-white/15 p-6">
                    <div class="flex items-start gap-4">
                        <div class="h-11 w-11 rounded-xl bg-emerald-400/15 ring-1 ring-emerald-300/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-emerald-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 20c-7 0-9-9-9-9S6 4 13 4c6 0 9 4 9 9 0 0-4 7-11 7Z"/>
                                <path d="M8 13c3 0 8-3 10-7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-serif font-semibold text-white">Turismo Sostenible</h3>
                            <p class="mt-1 text-sm text-white/75">
                                Viaja de manera responsable y ayuda a preservar el medio ambiente.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white/10 backdrop-blur-md ring-1 ring-white/15 p-6">
                    <div class="flex items-start gap-4">
                        <div class="h-11 w-11 rounded-xl bg-emerald-400/15 ring-1 ring-emerald-300/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-emerald-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M14.5 9.5 13 13l-3.5 1.5L11 11l3.5-1.5Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-serif font-semibold text-white">Experiencias Únicas</h3>
                            <p class="mt-1 text-sm text-white/75">
                                Vive aventuras inolvidables con guías expertos locales.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white/10 backdrop-blur-md ring-1 ring-white/15 p-6">
                    <div class="flex items-start gap-4">
                        <div class="h-11 w-11 rounded-xl bg-emerald-400/15 ring-1 ring-emerald-300/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-emerald-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4Z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-serif font-semibold text-white">Reserva Segura</h3>
                            <p class="mt-1 text-sm text-white/75">
                                Pago protegido y políticas de cancelación flexibles.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

  @include('layouts.footer')
@endsection
