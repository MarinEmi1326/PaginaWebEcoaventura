@extends('layouts.app')

@section('content')

<section class="bg-[#eef6fb] py-16">
  <div class="max-w-7xl mx-auto px-6">

    {{-- TÍTULO --}}
    <div class="text-center max-w-4xl mx-auto">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
        Servicios de Calidad
      </h1>
      <p class="mt-4 text-slate-600">
        Encuentra el hospedaje y restaurante perfecto para tu aventura. Servicios verificados y
        comprometidos con la sostenibilidad.
      </p>
    </div>

    {{-- BUSCADOR --}}
    <div class="mt-8 flex justify-center">
      <div class="w-full max-w-2xl">
        <div class="relative">
          <span class="absolute inset-y-0 left-4 flex items-center text-slate-500">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="7"></circle>
              <path d="M21 21l-4.3-4.3"></path>
            </svg>
          </span>
          <input
            type="text"
            placeholder="Buscar servicios..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-12 py-4 text-slate-900 placeholder:text-slate-500
                   shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-600/40"
          >
        </div>
      </div>
    </div>

    {{-- TABS + FILTROS --}}
    <div class="mt-10 flex items-center justify-between gap-4 flex-wrap">
      <div class="inline-flex items-center gap-2 rounded-2xl bg-emerald-900/10 p-2">
        <a href="{{ route('servicios.index') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->routeIs('servicios.index') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Todos
        </a>

        <a href="{{ route('servicios.tipo', 'hospedaje') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->is('servicios/hospedaje') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Hospedaje
        </a>

        <a href="{{ route('servicios.tipo', 'restaurantes') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->is('servicios/restaurantes') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Restaurantes
        </a>
      </div>

      <button
        type="button"
        class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 font-semibold text-emerald-950
               ring-1 ring-slate-200 shadow-sm hover:bg-slate-50 transition"
      >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 6h16M7 12h10M10 18h4"/>
        </svg>
        Filtros
      </button>
    </div>

    {{-- CARDS (diseño como tu imagen: imagen izquierda + info derecha) --}}
    @php
      $servicios = [
        [
          'tipo' => 'Hospedaje',
          'zona' => 'Sierra verde',
          'nombre' => 'Eco Lodge Paradise',
          'desc' => 'Lodge ecológico con vistas panorámicas a la selva. Habitaciones de lujo con materiales sostenibles.',
          'tags' => ['WiFi','Piscina','Spa','Restaurantes'],
          'telefono' => '+52 123 456 7890',
          'horario' => '24 horas',
          'rating' => '4.9',
          'reviews' => '156',
          'img' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1200&auto=format&fit=crop',
          'precio' => '$$$',
        ],
        [
          'tipo' => 'Hospedaje',
          'zona' => 'Sierra verde',
          'nombre' => 'Eco Lodge Paradise',
          'desc' => 'Lodge ecológico con vistas panorámicas a la selva. Habitaciones de lujo con materiales sostenibles.',
          'tags' => ['WiFi','Piscina','Spa','Restaurantes'],
          'telefono' => '+52 123 456 7890',
          'horario' => '24 horas',
          'rating' => '4.9',
          'reviews' => '156',
          'img' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200&auto=format&fit=crop',
          'precio' => '$$$',
        ],
      ];

      // Si vienes de /servicios/{tipo}, puedes filtrar visualmente (demo)
      $tipoActual = $tipo ?? null;
      if ($tipoActual) {
        $servicios = array_values(array_filter($servicios, function($s) use ($tipoActual){
          return strtolower($s['tipo']) === strtolower($tipoActual);
        }));
      }
    @endphp

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
      @forelse($servicios as $s)
        <article class="rounded-3xl bg-white ring-1 ring-slate-200/70 shadow-[0_20px_40px_rgba(2,6,23,0.08)] overflow-hidden">
          <div class="grid grid-cols-1 sm:grid-cols-[220px_1fr]">

            {{-- Imagen --}}
            <div class="relative">
              <img src="{{ $s['img'] }}" class="h-60 sm:h-full w-full object-cover" alt="Servicio">

              <span class="absolute top-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-emerald-950">
                {{ $s['tipo'] }}
              </span>

              <span class="absolute top-4 right-4 rounded-lg bg-emerald-900 px-3 py-1 text-xs font-extrabold text-white">
                {{ $s['precio'] }}
              </span>
            </div>

            {{-- Info --}}
            <div class="p-5">
              <div class="flex items-center gap-2 text-sm text-slate-700">
                <span class="text-lg leading-none">•</span>
                <span class="font-semibold">{{ $s['zona'] }}</span>
              </div>

              <h3 class="mt-1 text-lg font-extrabold text-slate-900">
                {{ $s['nombre'] }}
              </h3>

              <p class="mt-2 text-sm text-slate-600">
                {{ $s['desc'] }}
              </p>

              <div class="mt-3 flex flex-wrap gap-2">
                @foreach($s['tags'] as $t)
                  <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">
                    {{ $t }}
                  </span>
                @endforeach
              </div>

              <div class="mt-4 space-y-2 text-sm text-slate-700">
                <div class="flex items-center gap-2">
                  <span class="text-slate-500">•</span>
                  <span>{{ $s['telefono'] }}</span>
                </div>

                <div class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-emerald-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v6l4 2"/>
                  </svg>
                  <span>{{ $s['horario'] }}</span>
                </div>
              </div>

              <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                  <span class="inline-flex items-center gap-1">
                    <svg class="h-4 w-4 text-emerald-800" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 17.3l-5.4 3 1-6.1-4.4-4.3 6.1-.9L12 3.5l2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-3Z"/>
                    </svg>
                    {{ $s['rating'] }}
                  </span>
                  <span class="text-slate-500 font-medium">({{ $s['reviews'] }})</span>
                </div>

                <a href="#"
                   class="inline-flex items-center justify-center rounded-lg bg-emerald-900 px-4 py-2 text-xs font-extrabold text-white hover:bg-emerald-800 transition">
                  Reservar
                </a>
              </div>
            </div>

          </div>
        </article>
      @empty
        <div class="text-center text-slate-600">
          No hay servicios disponibles.
        </div>
      @endforelse
    </div>

  </div>
</section>

@endsection