@extends('layouts.app')

@section('content')

{{-- LISTADO DE DESTINOS (como tu diseño) --}}
<section class="bg-[#eef6fb] py-16">
  <div class="max-w-7xl mx-auto px-6">

    {{-- Título --}}
    <div class="text-center max-w-4xl mx-auto">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
        Descubre la magia de la naturaleza
      </h1>
      <p class="mt-4 text-slate-600">
        Vive experiencias únicas de ecoturismo. Explora destinos extraordinarios, conecta con la naturaleza y crea recuerdos
        inolvidables con aventuras sostenibles.
      </p>
    </div>

    {{-- Buscador --}}
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
            placeholder="Buscar destinos..."
            class="w-full rounded-2xl border border-slate-200 bg-white px-12 py-4 text-slate-900 placeholder:text-slate-500
                   shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-600/40"
          >
        </div>
      </div>
    </div>

    {{-- Tabs + filtros --}}
    <div class="mt-10 flex items-center justify-between gap-4 flex-wrap">
      <div class="inline-flex items-center gap-2 rounded-2xl bg-emerald-900/10 p-2">
        <a href="{{ route('destinos.index') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->routeIs('destinos.index') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Todos
        </a>

        <a href="{{ route('destinos.tipo', 'turisticos') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->is('destinos/turisticos') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Turisticos
        </a>

        <a href="{{ route('destinos.tipo', 'ecoturisticos') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->is('destinos/ecoturisticos') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Ecoturisticos
        </a>

        <a href="{{ route('destinos.tipo', 'balnearios') }}"
           class="px-5 py-2 rounded-xl font-semibold {{ request()->is('destinos/balnearios') ? 'bg-white shadow-sm text-emerald-950' : 'text-emerald-950/80 hover:bg-white/60' }}">
          Balnearios
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

    {{-- Cards demo --}}
    @php
      $cards = [
        ['tipo'=>'Ecoturístico','img'=>'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1200&auto=format&fit=crop'],
        ['tipo'=>'Balneario','img'=>'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?q=80&w=1200&auto=format&fit=crop'],
        ['tipo'=>'Turisticos','img'=>'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?q=80&w=1200&auto=format&fit=crop'],
      ];
    @endphp

    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($cards as $c)
        <article class="rounded-3xl bg-white shadow-[0_20px_40px_rgba(2,6,23,0.08)] ring-1 ring-slate-200/70 overflow-hidden">
          <div class="relative">
            <img src="{{ $c['img'] }}" class="h-52 w-full object-cover" alt="Destino">

            <span class="absolute top-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-emerald-950">
              {{ $c['tipo'] }}
            </span>

            <button class="absolute top-4 right-4 h-10 w-10 rounded-full bg-white/90 flex items-center justify-center">
              <svg class="h-5 w-5 text-emerald-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.8 4.6c-1.5-1.6-4-1.6-5.5 0L12 7.8 8.7 4.6c-1.5-1.6-4-1.6-5.5 0-1.8 1.9-1.8 4.9 0 6.8L12 21l8.8-9.6c1.8-1.9 1.8-4.9 0-6.8z"/>
              </svg>
            </button>

            <span class="absolute bottom-4 right-4 rounded-full bg-emerald-900 px-4 py-2 text-sm font-extrabold text-white">
              Desde $120
            </span>
          </div>

          <div class="p-5">
            <div class="text-xs font-semibold text-slate-500">Ecoturistico</div>
            <h3 class="mt-1 text-lg font-extrabold text-slate-900">Ruinas Ancestrales</h3>
            <p class="mt-2 text-sm text-slate-600">Descubre la historia antigua en este sitio arqueológico rodeado de naturaleza,</p>

            <div class="mt-3 flex flex-wrap gap-2">
              <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">Museo</span>
              <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">Guías expertos</span>
              <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700 ring-1 ring-slate-200">Tiendas</span>
            </div>

            <div class="mt-4 border-t border-slate-200 pt-4 flex items-center justify-between">
              <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                <span class="inline-flex items-center gap-1">
                  <svg class="h-4 w-4 text-emerald-800" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 17.3l-5.4 3 1-6.1-4.4-4.3 6.1-.9L12 3.5l2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-3Z"/>
                  </svg>
                  4.9
                </span>
                <span class="text-slate-500 font-medium">(312 reseñas)</span>
              </div>

              <a href="#" class="text-sm font-extrabold text-emerald-900 hover:text-emerald-700 transition">
                Ver detalles
              </a>
            </div>
          </div>
        </article>
      @endforeach
    </div>

  </div>
</section>

@endsection