@extends('layouts.admin')

@section('title', 'Nuevo Destino')

@section('content')

    <div class="mb-4">
        <a href="{{ route('misdestinos.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">Regresar a mis destinos</a>
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
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-geo-fill me-2"></i>Información del Destino
                </div>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre del destino <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               class="form-control rounded-3 py-2"
                               placeholder="Ej. Zona Arqueológica de Toniná" required maxlength="120">
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
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-map me-2"></i>Ubicación
                </div>
            </div>
            <div class="p-4">

                <p class="text-muted small mb-3">
                    <i class="bi bi-cursor-fill me-1"></i>
                    Haz clic en el mapa para marcar la ubicación del destino. Las coordenadas se llenarán automáticamente.
                </p>

                {{-- Mapa --}}
                <div id="mapa-destino" class="rounded-3 mb-3" style="height: 350px; width: 100%;"></div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Latitud</label>
                        <input type="number" step="0.0000001" name="lat" id="input-lat"
                               value="{{ old('lat') }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Se llena al hacer clic en el mapa">
                        @error('lat') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Longitud</label>
                        <input type="number" step="0.0000001" name="lng" id="input-lng"
                               value="{{ old('lng') }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Se llena al hacer clic en el mapa">
                        @error('lng') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    {{-- <div class="col-12">
                        <label class="form-label fw-bold">Google Place ID <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="text" name="google_place_id" value="{{ old('google_place_id') }}"
                               class="form-control rounded-3 py-2 font-monospace"
                               placeholder="Ej. ChIJN1t_tDeuEmsRUsoyG83frY4" maxlength="120">
                        @error('google_place_id') <div class="small text-danger mt-1">{{ $message }}</div> @enderror
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: Categorías --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-tags me-2"></i>Categorías
                </div>
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
                                           {{ in_array($cat->id_categoria, old('categorias', [])) ? 'checked' : '' }}>
                                    <span class="fw-semibold small">{{ $cat->nombre }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small text-center py-3">
                        <i class="bi bi-info-circle me-1"></i> Aún no hay categorías registradas. El administrador general debe crearlas primero.
                    </div>
                @endif
            </div>
        </div>

        {{-- BLOQUE 4: Actividades --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-run me-2"></i>Actividades
                </div>
            </div>
            <div class="p-4">

                @if ($actividadesExistentes->count() > 0)
                    <label class="form-label fw-bold mb-2">Selecciona actividades existentes</label>
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
                                           {{ in_array($act->id_actividad, old('actividades_existentes', [])) ? 'checked' : '' }}>
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
                                            <div class="text-muted mt-1" style="font-size:.72rem;">
                                                💡 {{ $act->recomendacion }}
                                            </div>
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
                    <div class="fw-semibold" style="color: var(--ea-text);">
                        <i class="bi bi-backpack me-2"></i>Paquetes / Talleres
                    </div>
                    <button type="button" class="btn btn-sm ea-btn-green rounded-3" onclick="agregarPaquete()">
                        <i class="bi bi-plus-lg me-1"></i> Agregar paquete
                    </button>
                </div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">Agrega los paquetes o talleres que ofrece este destino.</p>
                <div id="paquetes-container"></div>
                <div id="sin-paquetes" class="text-muted small text-center py-2">
                    <i class="bi bi-info-circle me-1"></i> Haz clic en "Agregar paquete" para registrar uno.
                </div>
            </div>
        </div>

        {{-- BLOQUE 6: Imágenes --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-images me-2"></i>Imágenes del Destino                    
                </div>
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
                        <label class="form-label fw-bold small">Dificultad <span class="text-danger">*</span></label>
                        <select name="nuevas_actividades[${i}][dificultad]" class="form-select rounded-3 py-2" >
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
    let contadorPaq = 0;

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
                               class="form-control rounded-3 py-2 font-monospace"
                               step="0.01" min="0" placeholder="Ej. 350.00">
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

    // ── Google Maps ──
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "{{ config('services.google_maps.key') }}",
        v: "weekly",
    });

    let mapaDestino, marcadorActivo;

    async function initMapaDestino() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        mapaDestino = new Map(document.getElementById("mapa-destino"), {
            zoom: 8,
            center: { lat: 16.862376997318453, lng: -92.05375658886717 },
            mapId: "DEMO_MAP_ID",
        });

        // Si ya hay valores (old input), poner marcador
        const latInicial = parseFloat(document.getElementById('input-lat').value);
        const lngInicial = parseFloat(document.getElementById('input-lng').value);
        if (!isNaN(latInicial) && !isNaN(lngInicial)) {
            marcadorActivo = new AdvancedMarkerElement({
                map: mapaDestino,
                position: { lat: latInicial, lng: lngInicial },
            });
            mapaDestino.setCenter({ lat: latInicial, lng: lngInicial });
            mapaDestino.setZoom(13);
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

    // ── Imágenes ──
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