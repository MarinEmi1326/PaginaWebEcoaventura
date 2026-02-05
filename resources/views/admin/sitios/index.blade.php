@extends('layouts.admin')

@section('title', 'Destinos')

@section('content')
@php
    $titulo = $categoria ? "Destinos {$categoria}" : "Destinos Turísticos";
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-4xl font-serif font-semibold text-slate-900">{{ $titulo }}</h1>
            <p class="text-slate-500 mt-1">Gestiona los destinos turísticos de la plataforma</p>
        </div>

        <a href="{{ route('admin.sitios.create', ['categoria' => request('categoria')]) }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 text-white font-semibold hover:bg-emerald-800 transition">
            <span class="text-lg">＋</span> Nuevo Destino
        </a>
    </div>

    {{-- Mensaje OK --}}
    @if (session('ok'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Filtros: buscar + categoria --}}
    <form method="GET"
          action="{{ route('admin.sitios.index') }}"
          class="bg-white border border-slate-200 rounded-2xl p-4
                 flex flex-col md:flex-row gap-3 md:items-center">

        {{-- Buscar por nombre --}}
        <div class="md:w-72">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
                <input
                    type="text"
                    name="buscar"
                    value="{{ request('buscar') }}"
                    placeholder="Buscar por nombre..."
                    class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-3
                           outline-none focus:ring-2 focus:ring-emerald-600/40
                           focus:border-emerald-700"
                >
            </div>
        </div>

        {{-- Filtro por categoría --}}
        <div class="md:w-64">
            <select name="categoria"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3
                           outline-none focus:ring-2 focus:ring-emerald-600/40
                           focus:border-emerald-700">
                <option value="" {{ request('categoria') ? '' : 'selected' }}>Todas</option>
                <option value="Turistico" {{ request('categoria') === 'Turistico' ? 'selected' : '' }}>Turístico</option>
                <option value="Ecoturistico" {{ request('categoria') === 'Ecoturistico' ? 'selected' : '' }}>Ecoturístico</option>
                <option value="Balneario" {{ request('categoria') === 'Balneario' ? 'selected' : '' }}>Balneario</option>
            </select>
        </div>

        {{-- Botones --}}
        <div class="flex gap-2">
            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition">
                Filtrar
            </button>

            <a href="{{ route('admin.sitios.index') }}"
               class="px-5 py-3 rounded-xl border-2 border-slate-400 text-slate-800 font-semibold
                      hover:bg-slate-100 hover:border-slate-500 transition">
                Limpiar
            </a>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between gap-4">
            <div class="text-2xl font-serif font-semibold text-slate-900">
                Destinos ({{ $sitios->count() }})
            </div>

            <div class="text-sm text-slate-500">
                @if(request('buscar'))
                    <span class="mr-2">🔎 "{{ request('buscar') }}"</span>
                @endif
                @if(request('categoria'))
                    <span>🏷️ {{ request('categoria') }}</span>
                @endif
            </div>
        </div>

        @if($sitios->count() === 0)
            <div class="p-10 text-center text-slate-600">
                <div class="text-4xl mb-2">🗺️</div>
                <div class="font-semibold">No hay destinos para mostrar</div>
                <div class="text-sm text-slate-500 mt-1">
                    Intenta cambiar el filtro o crea un nuevo destino.
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-500">
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-6 py-4 font-semibold">Imagen</th>
                            <th class="px-6 py-4 font-semibold">Nombre</th>
                            <th class="px-6 py-4 font-semibold">Ubicación</th>
                            <th class="px-6 py-4 font-semibold">Categoría</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sitios as $s)
                            @php
                                // ✅ link a editar con id + conservar filtros
                                $editUrl = route('admin.sitios.edit', [
                                    'sitio' => $s->id_sitio,
                                    'categoria' => request('categoria'),
                                    'buscar' => request('buscar'),
                                ]);
                            @endphp

                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                {{-- Imagen (click -> edit) --}}
                                <td class="px-6 py-4">
                                    <a href="{{ $editUrl }}" class="inline-block">
                                        @if(!empty($s->foto))
                                            <img src="{{ asset($s->foto) }}"
                                                 alt="Foto {{ $s->nombre }}"
                                                 class="h-12 w-12 rounded-xl object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500">
                                                📷
                                            </div>
                                        @endif
                                    </a>
                                </td>

                                {{-- Nombre (click -> edit) --}}
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    <a href="{{ $editUrl }}" class="hover:underline">
                                        {{ $s->nombre }}
                                    </a>
                                </td>

                                {{-- Ubicación --}}
                                <td class="px-6 py-4 text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400">📍</span>
                                        <span>{{ $s->comunidad }}, {{ $s->ciudad }}</span>
                                    </div>
                                </td>

                                {{-- Categoría --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full
                                                 bg-emerald-100 text-emerald-800 text-xs font-semibold">
                                        {{ $s->categoria }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-4">
                                        {{-- Editar --}}
                                        <a href="{{ $editUrl }}"
                                           class="text-slate-500 hover:text-slate-800"
                                           title="Editar">✏️</a>

                                       <form method="POST"
                                            action="{{ route('admin.sitios.destroy', $s->id_sitio) }}"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este destino?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700"
                                                    title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @endif
    </div>
</div>
@endsection
