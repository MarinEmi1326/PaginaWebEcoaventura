@extends('layouts.admin')

@section('title', 'Editar Ruta')

@section('content')

    <div class="mb-4">
        <a href="{{ route('rutas.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">
            Regresar a mis rutas
        </a>
        <h1 class="ea-page-title mt-3 mb-1">Editar Ruta</h1>
        <p class="ea-subtitle mb-0">Modifica la información de tu ruta turística.</p>
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

    @if (session('success'))
        <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('rutas.update', $ruta->id_ruta) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ══════════════════════════════════════
         BLOQUE 1: Campos editables
    ══════════════════════════════════════ --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-pencil-square me-2"></i>Información Editable
                </div>
            </div>
            <div class="p-4">
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre de la ruta <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre', $ruta->nombre) }}"
                            class="form-control rounded-3 py-2" required maxlength="120">
                        @error('nombre')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" rows="4" class="form-control rounded-3 py-2" required>{{ old('descripcion', $ruta->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BLOQUE: Recomendaciones del catálogo --}}
                    <div class="ea-card p-0 overflow-hidden mb-4">
                        <div class="p-4 border-bottom"
                            style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                            <div class="fw-semibold" style="color: var(--ea-text);">
                                <i class="bi bi-lightbulb me-2"></i>Recomendaciones
                            </div>
                        </div>
                        <div class="p-4">
                            @if ($recomendaciones->count() > 0)
                                <p class="text-muted small mb-3">
                                    Selecciona las recomendaciones que aplican para esta ruta.
                                </p>
                                <div class="row g-2">
                                    @foreach ($recomendaciones as $rec)
                                        <div class="col-12 col-md-4">
                                            <label class="d-flex align-items-center gap-2 p-3 rounded-3 border"
                                                style="cursor:pointer; background:#f7f9f7;">
                                                <input type="checkbox" name="recomendaciones[]"
                                                    value="{{ $rec->id_recomendacion }}"
                                                    class="form-check-input flex-shrink-0"
                                                    {{ in_array($rec->id_recomendacion, old('recomendaciones', $recomendacionesDelaRuta)) ? 'checked' : '' }}>
                                                <div>
                                                    <span class="fw-semibold small">{{ $rec->descripcion }}</span>
                                                    <div>
                                                        <span class="badge bg-light text-dark border"
                                                            style="font-size:.7rem;">
                                                            {{ ucfirst($rec->tipo) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted small text-center py-3">
                                    Aún no hay recomendaciones registradas.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
         BLOQUE 2: Campos de solo lectura
    ══════════════════════════════════════ --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="fw-semibold" style="color: var(--ea-text);">
                        <i class="bi bi-lock me-2"></i>Información No Editable
                    </div>
                    <span class="badge bg-secondary rounded-pill small">Solo lectura</span>
                </div>
            </div>
            <div class="p-4">
                <div class="p-3 rounded-3 mb-4" style="background:#fff8e1; border-left: 3px solid #FFA000;">
                    <div class="small" style="color:#795548;">
                        <i class="bi bi-info-circle me-1"></i>
                        Estos campos no se pueden modificar. Si necesitas cambiarlos,
                        elimina esta ruta y crea una nueva.
                    </div>
                </div>

                <div class="row g-3">

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small">Dificultad</label>
                        <input type="text" value="{{ ucfirst($ruta->dificultad) }}"
                            class="form-control rounded-3 py-2 bg-light" disabled>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small">Duración estimada</label>
                        <input type="text" value="{{ $ruta->duracion_estimada ?? 'No especificada' }}"
                            class="form-control rounded-3 py-2 bg-light" disabled>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small">Distancia (km)</label>
                        <input type="text" value="{{ $ruta->distancia_km ?? 'No especificada' }}"
                            class="form-control rounded-3 py-2 bg-light" disabled>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small">Fecha de inicio de operación</label>
                        <input type="text"
                            value="{{ $ruta->fecha_inicio_operacion ? \Carbon\Carbon::parse($ruta->fecha_inicio_operacion)->format('d/m/Y') : 'No especificada' }}"
                            class="form-control rounded-3 py-2 bg-light" disabled>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small">Fecha de fin de operación</label>
                        <input type="text"
                            value="{{ $ruta->fecha_fin_operacion ? \Carbon\Carbon::parse($ruta->fecha_fin_operacion)->format('d/m/Y') : 'No especificada' }}"
                            class="form-control rounded-3 py-2 bg-light" disabled>
                    </div>

                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
         BLOQUE 3: Destinos (solo lectura)
    ══════════════════════════════════════ --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="fw-semibold" style="color: var(--ea-text);">
                        <i class="bi bi-geo-alt me-2"></i>Paradas de la Ruta
                    </div>
                    <span class="badge bg-secondary rounded-pill small">Solo lectura</span>
                </div>
            </div>
            <div class="p-4">

                @forelse ($destinos as $destino)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-2"
                        style="background:#f7f9f7; border: 1px solid #e0e0e0;">
                        <div
                            style="
                        background: #1F6B4B; color: white;
                        border-radius: 50%; width: 32px; height: 32px;
                        display: flex; align-items: center; justify-content: center;
                        font-weight: bold; font-size: 13px; flex-shrink: 0;">
                            {{ $destino->orden }}
                        </div>
                        <span class="fw-semibold small" style="color: var(--ea-text);">
                            {{ $destino->nombre }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No hay paradas registradas.</p>
                @endforelse

            </div>
        </div>

        {{-- ══════════════════════════════════════
         BLOQUE 4: Imágenes actuales
    ══════════════════════════════════════ --}}
        @if ($imagenes->count() > 0)
            <div class="ea-card p-0 overflow-hidden mb-4">
                <div class="p-4 border-bottom"
                    style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                    <div class="fw-semibold" style="color: var(--ea-text);">
                        <i class="bi bi-images me-2"></i>Imágenes actuales
                    </div>
                </div>
                <div class="p-4">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($imagenes as $imagen)
                            <div class="position-relative" id="img-{{ $imagen->id_imagen }}">
                                <img src="{{ Storage::url($imagen->ruta_archivo) }}" class="rounded-3 border"
                                    style="width:120px; height:90px; object-fit:cover;" alt="Imagen de la ruta">
                                <button type="button" onclick="eliminarImagen({{ $imagen->id_imagen }})"
                                    class="btn btn-danger btn-sm rounded-circle position-absolute"
                                    style="top:-8px; right:-8px; width:24px; height:24px; padding:0; font-size:.7rem;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ══════════════════════════════════════
         BLOQUE 5: Agregar nuevas imágenes
    ══════════════════════════════════════ --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Agregar nuevas imágenes
                </div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">
                    Las imágenes que subas se agregarán a las existentes (JPG/PNG, máx. 5MB c/u).
                </p>

                <input type="file" id="fotosInput" name="fotos[]" accept="image/png,image/jpeg" class="d-none"
                    multiple>

                <div id="dropzone" class="rounded-3 border border-2 border-dashed p-5 text-center"
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

                @error('fotos')
                    <div class="small text-danger mt-1">{{ $message }}</div>
                @enderror
                @error('fotos.*')
                    <div class="small text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-2 pt-4 border-top"
            style="border-color: rgba(15,42,36,.10) !important;">
            <a href="{{ route('rutas.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
                Cancelar
            </a>
            <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-floppy me-1"></i> Guardar cambios
            </button>
        </div>

    </form>

    <script>
        // ── Eliminar imagen vía fetch ──
        async function eliminarImagen(id) {
            if (!confirm('¿Eliminar esta imagen?')) return;

            const res = await fetch(`/rutas/imagen/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (res.ok) {
                document.getElementById('img-' + id).remove();
            } else {
                alert('Error al eliminar la imagen.');
            }
        }

        // ── Dropzone de imágenes nuevas ──
        const fotosInput = document.getElementById('fotosInput');
        const dropzone = document.getElementById('dropzone');
        const previews = document.getElementById('previews');

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
        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.style.borderColor = '#1F6B4B';
        });
        dropzone.addEventListener('dragleave', () => {
            dropzone.style.borderColor = '#cdd8cd';
        });
        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.style.borderColor = '#cdd8cd';
            fotosInput.files = e.dataTransfer.files;
            mostrarPreviews(e.dataTransfer.files);
        });
    </script>

@endsection
