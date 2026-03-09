@extends('layouts.admin')

@section('title', 'Nuevo Destino')

@section('content')
<div class="max-w-3xl">
    {{-- Encabezado --}}
    <div class="mb-6">
        <a href="{{ route('admin.sitios.index', ['categoria' => request('categoria')]) }}"
           class="inline-flex items-center gap-2 mb-4 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
            ← Regresar a destinos
        </a>

        <h1 class="text-3xl font-serif font-semibold text-slate-900">Nuevo Destino</h1>
        <p class="text-slate-500 mt-1">Completa la información para crear un nuevo destino</p>
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

    {{-- Form --}}
    <form method="POST"
          action="{{ route('admin.sitios.store') }}"
          enctype="multipart/form-data"
          class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 space-y-6">
        @csrf

        {{-- Nombre --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">
                Nombre del Destino <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                   placeholder="Ej: Reserva Natural El Bosque"
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
            <input type="text" name="direccion" value="{{ old('direccion') }}"
                   placeholder="Ej: Carretera Ocosingo - Palenque Km 15"
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
                <input type="text" name="comunidad" value="{{ old('comunidad') }}"
                       placeholder="Ej: Lacanjá Chansayab"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="45">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Ciudad <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ciudad" value="{{ old('ciudad') }}"
                       placeholder="Ej: Ocosingo"
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
                      placeholder="Describe el destino..."
                      class="w-full rounded-xl border border-slate-300 px-4 py-3
                             outline-none focus:ring-2 focus:ring-emerald-600/40
                             focus:border-emerald-700"
                      required>{{ old('descripcion') }}</textarea>
        </div>

        {{-- Categoría + Costo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Categoría (oculta si viene desde filtro) --}}
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>

                @php
                    $catDefault = old('categoria') ?? ($categoria ?? null);
                @endphp

                @if(!empty($categoria))
                    <input type="hidden" name="categoria" value="{{ $categoria }}">
                    <div class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-slate-100 text-slate-700">
                        {{ $categoria }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">La categoría se asigna automáticamente por el filtro.</p>
                @else
                    <select name="categoria"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3
                                   outline-none focus:ring-2 focus:ring-emerald-600/40
                                   focus:border-emerald-700"
                            required>
                        <option value="" disabled {{ $catDefault ? '' : 'selected' }}>
                            Selecciona una categoría
                        </option>
                        <option value="Turistico" {{ $catDefault === 'Turistico' ? 'selected' : '' }}>Turístico</option>
                        <option value="Ecoturistico" {{ $catDefault === 'Ecoturistico' ? 'selected' : '' }}>Ecoturístico</option>
                        <option value="Balneario" {{ $catDefault === 'Balneario' ? 'selected' : '' }}>Balneario</option>
                    </select>
                @endif
            </div>

            {{-- Costo --}}
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Costo (MXN) <span class="text-red-500">*</span>
                </label>
                <input type="number" step="0.01" min="0"
                       name="costo" value="{{ old('costo', '0.00') }}"
                       placeholder="Ej: 150.00"
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
                <input type="text" name="telefono" value="{{ old('telefono') }}"
                       placeholder="Ej: 9611234567"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="10">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2">
                    Horario <span class="text-red-500">*</span>
                </label>
                <input type="text" name="horario" value="{{ old('horario') }}"
                       placeholder="Ej: 08:00 - 17:00"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3
                              outline-none focus:ring-2 focus:ring-emerald-600/40
                              focus:border-emerald-700"
                       required maxlength="45">
            </div>
        </div>

        {{-- Foto --}}
        <div>
            <div class="mb-2">
                <label class="block text-sm font-semibold text-slate-800">
                    Foto principal <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-slate-500 mt-1">Sube una foto (JPG/PNG) máx. 5MB.</p>
            </div>

            <input id="fotoInput" type="file" name="foto"
                   accept="image/png,image/jpeg"
                   class="hidden"
                   onchange="previewFoto(event)"
                   required>

            <div id="dropzone"
                 class="rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50
                        p-8 text-center cursor-pointer hover:bg-slate-100 transition">
                <div class="mx-auto h-12 w-12 rounded-2xl bg-emerald-100 flex items-center justify-center mb-3">
                    <span class="text-emerald-800 text-xl">⬆️</span>
                </div>
                <div class="text-sm font-semibold text-slate-800">Haz clic para subir o arrastra una imagen</div>
                <div class="text-xs text-slate-500 mt-1">PNG, JPG hasta 5MB</div>

                <img id="preview" src="" alt="Vista previa"
                     class="hidden mt-6 mx-auto max-h-56 rounded-xl border border-slate-200 object-cover" />
            </div>

            <script>
                const dropzone = document.getElementById('dropzone');
                const fotoInput = document.getElementById('fotoInput');

                dropzone.addEventListener('click', () => fotoInput.click());

                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-emerald-500');
                });

                dropzone.addEventListener('dragleave', () => {
                    dropzone.classList.remove('border-emerald-500');
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-emerald-500');

                    const file = e.dataTransfer.files?.[0];
                    if (!file) return;

                    fotoInput.files = e.dataTransfer.files;
                    previewFoto({ target: { files: [file] } });
                });

                function previewFoto(e) {
                    const file = e.target.files?.[0];
                    if (!file) return;

                    const img = document.getElementById('preview');
                    img.src = URL.createObjectURL(file);
                    img.classList.remove('hidden');
                }
            </script>
        </div>

        {{-- Info guía --}}
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">
                Información de guía (opcional)
            </label>
            <textarea name="info_guia" rows="3"
                      placeholder="Ej: recomendaciones, guía local, etc."
                      class="w-full rounded-xl border border-slate-300 px-4 py-3
                             outline-none focus:ring-2 focus:ring-emerald-600/40
                             focus:border-emerald-700">{{ old('info_guia') }}</textarea>
        </div>

        {{-- Botones --}}
        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('admin.sitios.index', ['categoria' => request('categoria')]) }}"
               class="px-5 py-3 rounded-xl border-2 border-slate-400
                      text-slate-800 font-semibold
                      hover:bg-slate-100 hover:border-slate-500
                      transition">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-emerald-700 text-white font-semibold hover:bg-emerald-800 transition">
                Crear
            </button>
        </div>

    </form>
</div>
@endsection
