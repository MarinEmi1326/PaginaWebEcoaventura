@extends('layouts.admin')

@section('title', 'Editar Destino')

@section('content')

    <div class="mb-4">
        <a href="{{ route('misdestinos.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">
            ← Regresar a mis destinos
        </a>
        <h1 class="ea-page-title mt-3 mb-1">Editar Destino</h1>
        <p class="ea-subtitle mb-0">Modifica la información de tu destino turístico.</p>
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

    <form action="{{ route('destinos.update', $destino->id_destino) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- BLOQUE 1: Información básica --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">📍 Información del Destino</div>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre del destino <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre', $destino->nombre) }}"
                               class="form-control rounded-3 py-2"
                               placeholder="Ej. Zona Arqueológica de Toniná" maxlength="120">
                        @error('nombre') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción completa <span class="text-danger">*</span></label>
                        <textarea name="descripcion" rows="4" class="form-control rounded-3 py-2"
                                  placeholder="Descripción detallada del destino...">{{ old('descripcion', $destino->descripcion) }}</textarea>
                        @error('descripcion') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Teléfono <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="text" name="telefono" value="{{ old('telefono', $destino->telefono) }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Ej. 9611234567" maxlength="20">
                        @error('telefono') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Recomendaciones <span class="text-muted small fw-normal">(opcional)</span></label>
                        <textarea name="recomendaciones" rows="3" class="form-control rounded-3 py-2"
                                  placeholder="Ej. Llevar repelente, calzado cómodo, agua...">{{ old('recomendaciones', $destino->recomendaciones) }}</textarea>
                        @error('recomendaciones') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 2: Ubicación con mapa --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🗺️ Ubicación</div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">
                    <i class="bi bi-cursor-fill me-1"></i>
                    Haz clic en el mapa para actualizar la ubicación del destino.
                </p>
                <div id="mapa-destino" class="rounded-3 mb-3" style="height: 350px; width: 100%;"></div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Latitud</label>
                        <input type="number" step="0.0000001" name="lat" id="input-lat"
                               value="{{ old('lat', $destino->lat) }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Se llena al hacer clic en el mapa">
                        @error('lat') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Longitud</label>
                        <input type="number" step="0.0000001" name="lng" id="input-lng"
                               value="{{ old('lng', $destino->lng) }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Se llena al hacer clic en el mapa">
                        @error('lng') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Google Place ID <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="text" name="google_place_id" value="{{ old('google_place_id', $destino->google_place_id) }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Ej. ChIJN1t_tDeuEmsRUsoyG83frY4" maxlength="120">
                        @error('google_place_id') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: Categorías --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🏷️ Categorías</div>
            </div>
            <div class="p-4">
                @if ($categorias->count() > 0)
                    <div class="row g-2">
                        @foreach ($categorias as $cat)
                            <div class="col-12 col-md-4">
                                <label class="d-flex align-items-center gap-2 p-3 rounded-3 border"
                                       style="cursor:pointer; background:#f7f9f7;"
                                       onmouseover="this.style.borderColor='var(--ea-green)'"
                                       onmouseout="this.style.borderColor=''">
                                    <input type="checkbox" name="categorias[]" value="{{ $cat->id_categoria }}"
                                           class="form-check-input flex-shrink-0"
                                           {{ in_array($cat->id_categoria, old('categorias', $categoriasDelDestino)) ? 'checked' : '' }}>
                                    <span class="fw-semibold small">{{ $cat->nombre }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small text-center py-3">
                        <i class="bi bi-info-circle me-1"></i> Aún no hay categorías registradas.
                    </div>
                @endif
            </div>
        </div>

        {{-- BLOQUE 4: Actividades --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🏃 Actividades</div>
            </div>
            <div class="p-4">
                @if ($actividadesExistentes->count() > 0)
                    <label class="form-label fw-bold mb-2">Selecciona actividades</label>
                    <div class="row g-2 mb-4">
                        @foreach ($actividadesExistentes as $act)
                            <div class="col-12 col-md-6">
                                <label class="d-flex align-items-start gap-2 p-3 rounded-3 border"
                                       style="cursor:pointer; background:#f7f9f7;"
                                       onmouseover="this.style.borderColor='var(--ea-green)'"
                                       onmouseout="this.style.borderColor=''">
                                    <input type="checkbox" name="actividades_existentes[]"
                                           value="{{ $act->id_actividad }}"
                                           class="form-check-input mt-1 flex-shrink-0"
                                           {{ in_array($act->id_actividad, old('actividades_existentes', $actividadesDelDestino)) ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-semibold small">{{ $act->nombre }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">
                                            <span class="badge bg-light text-dark border me-1">{{ ucfirst($act->dificultad) }}</span>
                                            @if ($act->duracion_estimada)
                                                <span class="me-1">⏱ {{ $act->duracion_estimada }}</span>
                                            @endif
                                            @if ($act->minimo_personas)
                                                <span class="me-1">👥 Mín. {{ $act->minimo_personas }} personas</span>
                                            @endif
                                        </div>
                                        @if ($act->recomendacion)
                                            <div class="text-muted mt-1" style="font-size:.72rem;">💡 {{ $act->recomendacion }}</div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <hr style="border-color: var(--ea-line);">
                @endif

                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
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

        {{-- BLOQUE 5: Paquetes --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-semibold" style="color: var(--ea-text);">🎒 Paquetes / Talleres</div>
                    <button type="button" class="btn btn-sm ea-btn-green rounded-3" onclick="agregarPaquete()">
                        <i class="bi bi-plus-lg me-1"></i> Agregar paquete
                    </button>
                </div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">Los paquetes existentes se reemplazarán al guardar. Agrega todos los que necesites.</p>
                <div id="paquetes-container">
                    @foreach ($paquetes as $paq)
                        @php $i = $loop->index + 1; @endphp
                        <div class="ea-card p-0 overflow-hidden mb-3 border" id="paquete-{{ $i }}">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center"
                                 style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.15);">
                                <span class="fw-semibold small" style="color: var(--ea-text);">Paquete #{{ $i }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-3" onclick="eliminarPaquete({{ $i }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="p-3">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold small">Nombre del paquete <span class="text-danger">*</span></label>
                                        <input type="text" name="paquetes[{{ $i }}][nombre]"
                                               value="{{ $paq->nombre }}"
                                               class="form-control rounded-3 py-2" maxlength="120">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label fw-bold small">Precio (MXN)</label>
                                        <input type="number" name="paquetes[{{ $i }}][precio]"
                                               value="{{ $paq->precio }}"
                                               class="form-control rounded-3 py-2 font-monospace"
                                               step="0.01" min="0">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label fw-bold small">Mínimo de personas</label>
                                        <input type="number" name="paquetes[{{ $i }}][minimo_personas]"
                                               value="{{ $paq->minimo_personas }}"
                                               class="form-control rounded-3 py-2" min="1">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Descripción</label>
                                        <textarea name="paquetes[{{ $i }}][descripcion]"
                                                  class="form-control rounded-3 py-2" rows="2">{{ $paq->descripcion }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($paquetes->isEmpty())
                    <div id="sin-paquetes" class="text-muted small text-center py-2">
                        <i class="bi bi-info-circle me-1"></i> No hay paquetes registrados. Haz clic en "Agregar paquete".
                    </div>
                @else
                    <div id="sin-paquetes" class="d-none"></div>
                @endif
            </div>
        </div>

        {{-- BLOQUE 6: Imágenes existentes --}}
        @if ($imagenes->count() > 0)
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">🖼️ Imágenes actuales</div>
            </div>
            <div class="p-4">
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($imagenes as $img)
                        <div class="position-relative" id="img-{{ $img->id_imagen }}">
                            <img src="{{ Storage::url($img->ruta_archivo) }}"
                                 class="rounded-3 border"
                                 style="width:120px; height:90px; object-fit:cover;">
                            <button type="button"
                                    onclick="eliminarImagen({{ $img->id_imagen }})"
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

        {{-- BLOQUE 7: Agregar nuevas imágenes --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">📸 Agregar nuevas imágenes</div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">Las imágenes que subas se agregarán a las existentes (JPG/PNG, máx. 5MB c/u).</p>
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
            </div>
        </div>

        {{-- Acciones --}}
        <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-2 pt-4 border-top"
             style="border-color: rgba(15,42,36,.10) !important;">
            <a href="{{ route('misdestinos.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
                Cancelar
            </a>
            <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-floppy me-1"></i> Guardar cambios
            </button>
        </div>

    </form>

<script>
    // ── Eliminar imagen vía fetch (sin form anidado) ──
    async function eliminarImagen(id) {
        if (!confirm('¿Eliminar esta imagen?')) return;
        const res = await fetch(`/destinos/imagen/${id}`, {
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

    // ── Google Maps ──
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "{{ config('services.google_maps.key') }}",
        v: "weekly",
    });

    let mapaDestino, marcadorActivo;

    async function initMapaDestino() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        const latInicial = parseFloat(document.getElementById('input-lat').value) || 16.862376997318453;
        const lngInicial = parseFloat(document.getElementById('input-lng').value) || -92.05375658886717;
        const tieneUbicacion = !!document.getElementById('input-lat').value;

        mapaDestino = new Map(document.getElementById("mapa-destino"), {
            zoom: tieneUbicacion ? 13 : 8,
            center: { lat: latInicial, lng: lngInicial },
            mapId: "DEMO_MAP_ID",
        });

        if (tieneUbicacion) {
            marcadorActivo = new AdvancedMarkerElement({
                map: mapaDestino,
                position: { lat: latInicial, lng: lngInicial },
            });
        }

        mapaDestino.addListener("click", async (e) => {
            const lat = e.latLng.lat();
            const lng = e.latLng.lng();
            document.getElementById('input-lat').value = lat.toFixed(7);
            document.getElementById('input-lng').value = lng.toFixed(7);
            if (marcadorActivo) marcadorActivo.map = null;
            marcadorActivo = new AdvancedMarkerElement({
                map: mapaDestino,
                position: { lat, lng },
            });
        });
    }
    initMapaDestino();

    // ── Actividades nuevas ──
    let contadorAct = 0;

    function agregarActividad() {
        contadorAct++;
        const i = contadorAct;
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
                               placeholder="Ej. Senderismo, Kayak..." maxlength="80">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">Dificultad</label>
                        <select name="nuevas_actividades[${i}][dificultad]" class="form-select rounded-3 py-2">
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

    // ── Paquetes ──
    let contadorPaq = {{ $paquetes->count() }};

    function agregarPaquete() {
        contadorPaq++;
        const i = contadorPaq;
        document.getElementById('sin-paquetes').classList.add('d-none');
        const html = `
        <div class="ea-card p-0 overflow-hidden mb-3 border" id="paquete-${i}">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center"
                 style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.15);">
                <span class="fw-semibold small" style="color: var(--ea-text);">Paquete #${i}</span>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-3" onclick="eliminarPaquete(${i})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="p-3">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small">Nombre del paquete <span class="text-danger">*</span></label>
                        <input type="text" name="paquetes[${i}][nombre]"
                               class="form-control rounded-3 py-2"
                               placeholder="Ej. Tour guiado, Taller de pesca..." maxlength="120">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">Precio (MXN)</label>
                        <input type="number" name="paquetes[${i}][precio]"
                               class="form-control rounded-3 py-2 font-monospace" step="0.01" min="0" placeholder="Ej. 350.00">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">Mínimo de personas</label>
                        <input type="number" name="paquetes[${i}][minimo_personas]"
                               class="form-control rounded-3 py-2" min="1" placeholder="Ej. 4">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Descripción</label>
                        <textarea name="paquetes[${i}][descripcion]"
                                  class="form-control rounded-3 py-2" rows="2"
                                  placeholder="Describe qué incluye el paquete..."></textarea>
                    </div>
                </div>
            </div>
        </div>`;
        document.getElementById('paquetes-container').insertAdjacentHTML('beforeend', html);
    }

    function eliminarPaquete(i) {
        document.getElementById('paquete-' + i).remove();
        if (document.getElementById('paquetes-container').children.length === 0) {
            document.getElementById('sin-paquetes').classList.remove('d-none');
        }
    }

    // ── Imágenes nuevas ──
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