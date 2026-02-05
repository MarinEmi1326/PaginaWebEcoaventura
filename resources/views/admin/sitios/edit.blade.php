@extends('layouts.admin')

@section('title', 'Editar Destino')

@section('content')
<div class="max-w-3xl">
    {{-- Encabezado --}}
    <div class="mb-6">
        <a href="{{ route('admin.sitios.index', ['categoria' => request('categoria'), 'buscar' => request('buscar')]) }}"
           class="inline-flex items-center gap-2 mb-4 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
            ← Regresar a destinos
        </a>

        <h1 class="text-3xl font-serif font-semibold text-slate-900">Editar Destino</h1>
        <p class="text-slate-500 mt-1">Actualiza la información del destino</p>
    </div>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
            <div class="font-semibold mb-2">Revisa los campos:</div>
            <ul class="list-disc ml-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.sitios.update', $sitio->id_sitio) }}"
          enctype="multipart/form-data"
          class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 space-y-6">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">
                Nombre del Destino <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre"
                   value="{{ old('nombre', $sitio->nombre) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3
                          outline-none focus:ring-2 focus:ring-emerald-600/40
                          focus:border-emerald-700"
                   required maxlength="45">
        </div>

        {{-- Dirección --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">
                Dirección <span class="text-red-500">*</span>
            </label>
            <input type="text" name="direccion"
                   value="{{ old('direccion', $sitio->direccion) }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3
                          outline-none focus:ring-2 focus:ring-emerald-600/40
                          focus:border-emerald-700"
                   required maxlength="120">
        </div>

        {{-- Comunidad + Ciudad --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Comunidad <span class="text-red-500">*</span>
                </label>
                <input type="text" name="comunidad"
                       value="{{ old('comunidad', $sitio->comunidad) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="45">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Ciudad <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ciudad"
                       value="{{ old('ciudad', $sitio->ciudad) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="45">
            </div>
        </div>

        {{-- Descripción --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">
                Descripción <span class="text-red-500">*</span>
            </label>
            <textarea name="descripcion" rows="4"
                      class="w-full rounded-xl border border-slate-300 px-4 py-3
                             outline-none focus:ring-2 focus:ring-emerald-600/40
                             focus:border-emerald-700"
                      required>{{ old('descripcion', $sitio->descripcion) }}</textarea>
        </div>

        {{-- Categoría + Costo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>

                @php
                    $catDefault = old('categoria', $sitio->categoria);
                @endphp

                {{-- Si vienes desde filtro, no mostramos select --}}
                @if(!empty(request('categoria')))
                    <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                    <div class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-slate-100 text-slate-700">
                        {{ request('categoria') }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">La categoría se mantiene por el filtro.</p>
                @else
                    <select name="categoria"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   outline-none focus:ring-2 focus:ring-emerald-600/40
                                   focus:border-emerald-700"
                            required>
                        <option value="Turistico" {{ $catDefault === 'Turistico' ? 'selected' : '' }}>Turístico</option>
                        <option value="Ecoturistico" {{ $catDefault === 'Ecoturistico' ? 'selected' : '' }}>Ecoturístico</option>
                        <option value="Balneario" {{ $catDefault === 'Balneario' ? 'selected' : '' }}>Balneario</option>
                    </select>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Costo (MXN) <span class="text-red-500">*</span>
                </label>
                <input type="number" step="0.01" min="0"
                       name="costo"
                       value="{{ old('costo', $sitio->costo) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required>
            </div>
        </div>

        {{-- Teléfono + Horario --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Teléfono <span class="text-red-500">*</span>
                </label>
                <input type="text" name="telefono"
                       value="{{ old('telefono', $sitio->telefono) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="10">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Horario <span class="text-red-500">*</span>
                </label>
                <input type="text" name="horario"
                       value="{{ old('horario', $sitio->horario) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="45">
            </div>
        </div>

        {{-- Foto actual + nueva opcional --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800">
                Foto principal (opcional)
            </label>
            <p class="text-xs text-slate-500 mt-1">Si subes una nueva, se reemplazará la anterior.</p>

            {{-- Foto actual --}}
            @if(!empty($sitio->foto))
                <div class="mt-3">
                    <div class="text-xs text-slate-500 mb-2">Foto actual:</div>
                    <img src="{{ asset($sitio->foto) }}"
                         class="h-40 w-full rounded-2xl object-cover border border-slate-200"
                         alt="Foto actual">
                </div>
            @endif

            {{-- Input nuevo --}}
            <input id="fotoInput" type="file" name="foto"
                   accept="image/png,image/jpeg,image/webp"
                   class="mt-4 block w-full rounded-xl border border-slate-300 px-4 py-3">

            <div class="mt-3">
                <img id="preview" src="" alt="Vista previa"
                     class="hidden mt-2 max-h-56 rounded-xl border border-slate-200 object-cover" />
            </div>

            <script>
                const fotoInput = document.getElementById('fotoInput');
                fotoInput.addEventListener('change', (e) => {
                    const file = e.target.files?.[0];
                    if (!file) return;
                    const img = document.getElementById('preview');
                    img.src = URL.createObjectURL(file);
                    img.classList.remove('hidden');
                });
            </script>
        </div>

        {{-- Info guía --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">
                Información de guía (opcional)
            </label>
            <textarea name="info_guia" rows="3"
                      class="w-full rounded-xl border border-slate-300 px-4 py-3
                             outline-none focus:ring-2 focus:ring-emerald-600/40
                             focus:border-emerald-700">{{ old('info_guia', $sitio->info_guia) }}</textarea>
        </div>

        {{-- Botones --}}
        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('admin.sitios.index', ['categoria' => request('categoria'), 'buscar' => request('buscar')]) }}"
               class="px-5 py-3 rounded-xl border-2 border-slate-400
                      text-slate-800 font-semibold
                      hover:bg-slate-100 hover:border-slate-500 transition">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-emerald-700 text-white font-semibold hover:bg-emerald-800 transition">
                Guardar cambios
            </button>
        </div>

    </form>
</div>
@endsection
