@extends('layouts.admin')

@section('title', 'Nuevo Destino')

@section('content')

    <div class="mb-4">
        <a href="{{ route('misdestinos.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">
            ← Regresar a mis destinos
        </a>
        <h1 class="ea-page-title mt-3 mb-1">Crear Nuevo Destino</h1>
        <p class="ea-subtitle mb-0">Completa la información para registrar un nuevo destino turístico.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4">
            <div class="fw-semibold mb-1">Revisa los campos:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('destinos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- BLOQUE 1: Información básica --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">📍 Información del Destino</div>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre del destino <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               class="form-control rounded-3 py-2"
                               placeholder="Ej. Zona Arqueológica de Toniná"
                               required maxlength="120">
                        @error('nombre') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción completa <span class="text-danger">*</span></label>
                        <textarea name="descripcion" rows="4" class="form-control rounded-3 py-2"
                                  placeholder="Descripción detallada del destino..." required>{{ old('descripcion') }}</textarea>
                        @error('descripcion') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Teléfono <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Ej. 9611234567" maxlength="20">
                        @error('telefono') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Recomendaciones <span class="text-muted small fw-normal">(opcional)</span></label>
                        <textarea name="recomendaciones" rows="3" class="form-control rounded-3 py-2"
                                  placeholder="Ej. Llevar repelente, calzado cómodo, agua...">{{ old('recomendaciones') }}</textarea>
                        @error('recomendaciones') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 2: Ubicación --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🗺️ Ubicación</div>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Latitud <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="number" step="0.0000001" name="lat" value="{{ old('lat') }}"
                               class="form-control rounded-3 py-2 font-monospace" placeholder="Ej. 16.9076543">
                        @error('lat') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Longitud <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="number" step="0.0000001" name="lng" value="{{ old('lng') }}"
                               class="form-control rounded-3 py-2 font-monospace" placeholder="Ej. -92.1234567">
                        @error('lng') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Google Place ID <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="text" name="google_place_id" value="{{ old('google_place_id') }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Ej. ChIJN1t_tDeuEmsRUsoyG83frY4" maxlength="120">
                        @error('google_place_id') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: Actividades --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🏃 Actividades</div>
            </div>
            <div class="p-4">

                {{-- Actividades existentes --}}
                @if ($actividadesExistentes->count() > 0)
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-2">Selecciona actividades existentes</label>
                        <div class="row g-2">
                            @foreach ($actividadesExistentes as $act)
                                <div class="col-12 col-md-6">
                                    <label class="d-flex align-items-start gap-2 p-3 rounded-3 border"
                                           style="cursor:pointer; background:#f7f9f7;"
                                           onmouseover="this.style.borderColor='var(--ea-green)'"
                                           onmouseout="this.style.borderColor=''">
                                        <input type="checkbox"
                                               name="actividades_existentes[]"
                                               value="{{ $act->id_actividad }}"
                                               class="form-check-input mt-1 flex-shrink-0"
                                               {{ in_array($act->id_actividad, old('actividades_existentes', [])) ? 'checked' : '' }}>
                                        <div>
                                            <div class="fw-semibold small">{{ $act->nombre }}</div>
                                            <div class="text-muted" style="font-size:.75rem;">
                                                Dificultad: {{ ucfirst($act->dificultad) }}
                                                @if ($act->duracion_estimada) · {{ $act->duracion_estimada }} @endif
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr style="border-color: var(--ea-line);">
                @endif

                {{-- Nueva actividad --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold small" style="color: var(--ea-text);">¿No encuentras la actividad? Crea una nueva</span>
                    <button type="button" class="btn btn-sm ea-btn-green rounded-3" onclick="agregarActividad()">
                        <i class="bi bi-plus-lg me-1"></i> Nueva actividad
                    </button>
                </div>

                <div id="nuevas-actividades-container"></div>

                <div id="sin-nuevas" class="text-muted small text-center py-2">
                    <i class="bi bi-info-circle me-1"></i> Haz clic en "Nueva actividad" para agregar una que no esté en el listado.
                </div>

            </div>
        </div>

        {{-- BLOQUE 4: Imágenes --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🖼️ Imágenes del Destino</div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">Puedes subir varias imágenes (JPG/PNG, máx. 5MB c/u).</p>
                <input type="file" id="fotosInput" name="fotos[]" accept="image/png,image/jpeg" class="d-none" multiple>
                <div id="dropzone"
                     class="rounded-3 border border-2 border-dashed p-5 text-center"
                     style="border-color: #cdd8cd !important; background: #f7f9f7; cursor:pointer;"
                     onclick="document.getElementById('fotosInput').click()">
                    <div class="mx-auto mb-3 rounded-3 d-inline-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;background:#e2ece9;">
                        <i class="bi bi-cloud-arrow-up text-success fs-4"></i>
                    </div>
                    <div class="fw-semibold text-dark">Haz clic para subir o arrastra imágenes</div>
                    <div class="small text-muted mt-1">PNG, JPG hasta 5MB cada una</div>
                </div>
                <div id="previews" class="d-flex flex-wrap gap-2 mt-3"></div>
                @error('fotos') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                @error('fotos.*') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Acciones --}}
        <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-2 pt-4 border-top"
             style="border-color: rgba(15,42,36,.10) !important;">
            <a href="{{ route('misdestinos.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
                Cancelar
            </a>
            <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-send me-1"></i> Crear Destino
            </button>
        </div>

    </form>

<script>
    let contador = 0;

    function agregarActividad() {
        contador++;
        const i = contador;
        document.getElementById('sin-nuevas').classList.add('d-none');

        const html = `
        <div class="ea-card p-0 overflow-hidden mb-3 border" id="nueva-act-${i}">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center"
                 style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.15);">
                <span class="fw-semibold small" style="color: var(--ea-text);">Nueva actividad #${i}</span>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-3" onclick="eliminarActividad(${i})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="p-3">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nuevas_actividades[${i}][nombre]"
                               class="form-control rounded-3 py-2"
                               placeholder="Ej. Senderismo, Kayak, Rappel..." maxlength="80" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">Dificultad <span class="text-danger">*</span></label>
                        <select name="nuevas_actividades[${i}][dificultad]" class="form-select rounded-3 py-2" required>
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">Duración estimada</label>
                        <input type="text" name="nuevas_actividades[${i}][duracion]"
                               class="form-control rounded-3 py-2" placeholder="Ej. 2 horas" maxlength="50">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small">Mínimo de personas</label>
                        <input type="number" name="nuevas_actividades[${i}][min_personas]"
                               class="form-control rounded-3 py-2" min="1" placeholder="Ej. 2">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Recomendación</label>
                        <textarea name="nuevas_actividades[${i}][recomendacion]"
                                  class="form-control rounded-3 py-2" rows="2"
                                  placeholder="Ej. No recomendado para personas con vértigo..."></textarea>
                    </div>
                </div>
            </div>
        </div>`;

        document.getElementById('nuevas-actividades-container').insertAdjacentHTML('beforeend', html);
    }

    function eliminarActividad(i) {
        document.getElementById('nueva-act-' + i).remove();
        if (document.getElementById('nuevas-actividades-container').children.length === 0) {
            document.getElementById('sin-nuevas').classList.remove('d-none');
        }
    }

    // Imágenes
    const fotosInput = document.getElementById('fotosInput');
    const dropzone   = document.getElementById('dropzone');
    const previews   = document.getElementById('previews');

    function mostrarPreviews(files) {
        previews.innerHTML = '';
        Array.from(files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'rounded-3 border';
            img.style.cssText = 'width:100px;height:80px;object-fit:cover;';
            previews.appendChild(img);
        });
    }

    fotosInput.addEventListener('change', e => mostrarPreviews(e.target.files));
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = '#1F6B4B'; });
    dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = '#cdd8cd'; });
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.style.borderColor = '#cdd8cd';
        fotosInput.files = e.dataTransfer.files;
        mostrarPreviews(e.dataTransfer.files);
    });
</script>

@endsection