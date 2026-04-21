@extends('layouts.admin')

@section('title', 'Agregar Ruta')

@section('content')

    <div class="mb-4">
        <a href="{{ route('rutas.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">
            Regresar a mis rutas
        </a>
        <h1 class="ea-page-title mt-3 mb-1">Crear Nueva Ruta</h1>
        <p class="ea-subtitle mb-0">Conecta destinos existentes para formar una ruta turística.</p>
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

    <form action="{{ route('rutas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- BLOQUE 1: Información general --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-signpost-2 me-2"></i>Información General
                </div>
            </div>
            <div class="p-4">
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre de la ruta <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control rounded-3 py-2"
                            placeholder="Ej. Ruta de los Altos de Chiapas" required maxlength="120">
                        @error('nombre')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" rows="4" class="form-control rounded-3 py-2"
                            placeholder="Describe el recorrido, paisajes, experiencia general..." required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold">Dificultad <span class="text-danger">*</span></label>
                        <select name="dificultad" class="form-select rounded-3 py-2" required>
                            <option value="baja" {{ old('dificultad') == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('dificultad') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('dificultad') == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
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
                                                    {{ in_array($rec->id_recomendacion, old('recomendaciones', [])) ? 'checked' : '' }}>
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
                                    Aún no hay recomendaciones registradas. El administrador general debe crearlas primero.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- BLOQUE 2: Resumen --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-calendar3 me-2"></i>Resumen de la Ruta
                </div>
            </div>
            <div class="p-4">
                <div class="row g-3">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Duración estimada</label>
                        <input type="text" name="duracion_estimada" value="{{ old('duracion_estimada') }}"
                            class="form-control rounded-3 py-2" placeholder="Ej. 1 día, 6 horas" maxlength="50">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Distancia (km)</label>
                        <input type="number" name="distancia_km" value="{{ old('distancia_km') }}"
                            class="form-control rounded-3 py-2 font-monospace" step="0.01" min="0"
                            placeholder="Ej. 45.5">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">
                            Fecha de inicio de operación
                            <span class="text-muted small fw-normal">(opcional)</span>
                        </label>
                        <input type="date" name="fecha_inicio_operacion" value="{{ old('fecha_inicio_operacion') }}"
                            class="form-control rounded-3 py-2">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">
                            Fecha de fin de operación
                            <span class="text-muted small fw-normal">(opcional)</span>
                        </label>
                        <input type="date" name="fecha_fin_operacion" value="{{ old('fecha_fin_operacion') }}"
                            class="form-control rounded-3 py-2">
                    </div>

                </div>
            </div>
        </div>

        {{-- BLOQUE 3: Destinos --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-geo-alt me-2"></i>Destinos de la Ruta
                </div>
            </div>
            <div class="p-4">

                <label class="form-label fw-bold">Destinos de la ruta <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-2 px-3 rounded-3 fw-semibold small"
                        style="background:#f0f7f4; border: 1px solid var(--ea-green); color: var(--ea-green); min-width: 120px;">
                        <i class="bi bi-geo-alt me-1"></i>
                        <span id="contador-paradas">0</span> Destino(s)
                    </div>
                    <button type="button" class="btn ea-btn-green rounded-3" onclick="agregarParada()">
                        <i class="bi bi-plus-lg me-1"></i> Agregar Destino
                    </button>
                </div>

                <div id="contenedor-paradas"></div>

                @error('destinos')
                    <div class="small text-danger mt-2">{{ $message }}</div>
                @enderror

            </div>
        </div>

        {{-- BLOQUE 4: Mapa --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-map me-2"></i>Mapa de la Ruta
                </div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Los destinos aparecerán en el mapa conforme los vayas seleccionando.
                </p>
                <div id="mapa-ruta" class="rounded-3" style="height: 400px; width: 100%;"></div>
            </div>
        </div>

        {{-- BLOQUE 5: Imágenes --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"
                style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
                <div class="fw-semibold" style="color: var(--ea-text);">
                    <i class="bi bi-images me-2"></i>Imágenes de la Ruta
                </div>
            </div>
            <div class="p-4">
                <p class="text-muted small mb-3">Puedes subir varias imágenes (JPG/PNG, máx. 5MB c/u).</p>
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
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-2 pt-4 border-top"
            style="border-color: rgba(15,42,36,.10) !important;">
            <a href="{{ route('rutas.index') }}"
                class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">Cancelar</a>
            <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-send me-1"></i> Guardar Ruta
            </button>
        </div>

    </form>

    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })({
            key: "{{ config('services.google_maps.key') }}",
            v: "weekly",
        });

        let mapaRuta;
        let marcadores = [];
        let lineaRuta = null;
        let puntosRuta = {};
        let directionsService = null;
        let directionsRenderer = null;
        let contadorParadas = 0;

        const opcionesDestinos = @json($destinos->map(fn($d) => ['id' => $d->id_destino, 'nombre' => $d->nombre]));

        function agregarParada() {
            contadorParadas++;
            const id = contadorParadas;

            let options = '<option value="">-- Selecciona un destino --</option>';
            opcionesDestinos.forEach(d => {
                options += `<option value="${d.id}">${d.nombre}</option>`;
            });

            document.getElementById('contenedor-paradas').insertAdjacentHTML('beforeend', `
        <div class="ea-card p-0 overflow-hidden mb-3 border" id="bloque-${id}">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between"
                 style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.15);">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-white fw-semibold num-parada"
                          style="background: var(--ea-green); min-width: 28px;">?</span>
                    <span class="fw-semibold small etiqueta-parada" style="color: var(--ea-text);">Parada</span>
                </div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger rounded-3"
                        onclick="eliminarParada(${id})"
                        title="Eliminar esta parada">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="p-3">
                <label class="form-label fw-bold small">Destino <span class="text-danger">*</span></label>
                <select name="destinos[]"
                        data-bloque="${id}"
                        class="form-select rounded-3 py-2 mb-2"
                        onchange="cargarInfoDestino(this)">
                    ${options}
                </select>
                <div id="info-parada-${id}" style="display:none;">
                    <div id="desc-parada-${id}"
                         class="p-3 rounded-3 small mb-3"
                         style="background:#f0f7f4; border-left: 3px solid var(--ea-green); color: #333;">
                    </div>
                    <label class="form-label fw-bold small mb-2">
                        Actividades disponibles
                        <span class="text-muted fw-normal">(selecciona las que se realizarán en esta parada)</span>
                    </label>
                    <div id="actividades-parada-${id}" class="row g-2"></div>
                </div>
            </div>
        </div>
    `);

            recalcularNumeros();
        }

        function eliminarParada(id) {
            const bloque = document.getElementById(`bloque-${id}`);
            if (!bloque) return;
            const ordenActual = obtenerOrdenDeBloque(id);
            if (ordenActual !== null) delete puntosRuta[ordenActual];
            bloque.remove();
            recalcularNumeros();
            recalcularPuntosRuta();
            actualizarMapa();
        }

        function recalcularNumeros() {
            const bloques = document.querySelectorAll('#contenedor-paradas > div[id^="bloque-"]');
            bloques.forEach((bloque, index) => {
                const numero = index + 1;
                const badge = bloque.querySelector('.num-parada');
                const etiqueta = bloque.querySelector('.etiqueta-parada');
                if (badge) badge.textContent = numero;
                if (etiqueta) etiqueta.textContent = numero === 1 ? 'Parada 1 — Lugar de encuentro' :
                    `Parada ${numero}`;
            });
            document.getElementById('contador-paradas').textContent = bloques.length;
        }

        function obtenerOrdenDeBloque(id) {
            const bloques = document.querySelectorAll('#contenedor-paradas > div[id^="bloque-"]');
            let orden = null;
            bloques.forEach((bloque, index) => {
                if (bloque.id === `bloque-${id}`) orden = index + 1;
            });
            return orden;
        }

        function recalcularPuntosRuta() {
            const nuevosPuntos = {};
            const bloques = document.querySelectorAll('#contenedor-paradas > div[id^="bloque-"]');
            bloques.forEach((bloque, index) => {
                const orden = index + 1;
                const select = bloque.querySelector('select[data-bloque]');
                const idDestino = select ? select.value : null;
                if (idDestino) {
                    for (const punto of Object.values(puntosRuta)) {
                        if (punto.idDestino == idDestino) {
                            nuevosPuntos[orden] = punto;
                            break;
                        }
                    }
                }
            });
            puntosRuta = nuevosPuntos;
        }

        function cargarInfoDestino(selectElement) {
            const idDestino = selectElement.value;
            const idBloque = selectElement.dataset.bloque;

            const infoDiv = document.getElementById(`info-parada-${idBloque}`);
            const descDiv = document.getElementById(`desc-parada-${idBloque}`);
            const actividadesDiv = document.getElementById(`actividades-parada-${idBloque}`);

            if (!idDestino) {
                infoDiv.style.display = 'none';
                recalcularPuntosRuta();
                actualizarMapa();
                return;
            }

            fetch(`/api/destino-info/${idDestino}`)
                .then(res => res.json())
                .then(data => {

                    descDiv.textContent = data.descripcion;

                    if (data.actividades.length === 0) {
                        actividadesDiv.innerHTML = `
                    <div class="col-12">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Este destino no tiene actividades registradas.
                        </p>
                    </div>`;
                    } else {
                        actividadesDiv.innerHTML = data.actividades.map(a => `
                    <div class="col-12 col-md-4">
                        <label class="d-flex align-items-center gap-2 p-3 rounded-3 border"
                               style="cursor:pointer; background:#f7f9f7;"
                               onmouseover="this.style.borderColor='var(--ea-green)'"
                               onmouseout="this.style.borderColor=''">
                            <input type="checkbox"
                                   name="actividades_${idDestino}[]"
                                   value="${a.id_actividad}"
                                   class="form-check-input flex-shrink-0">
                            <span class="fw-semibold small">${a.nombre}</span>
                        </label>
                    </div>
                `).join('');
                    }

                    infoDiv.style.display = 'block';

                    const bloques = document.querySelectorAll('#contenedor-paradas > div[id^="bloque-"]');
                    let ordenReal = 1;
                    bloques.forEach((bloque, index) => {
                        if (bloque.id === `bloque-${idBloque}`) ordenReal = index + 1;
                    });

                    if (data.lat && data.lng) {
                        puntosRuta[ordenReal] = {
                            lat: parseFloat(data.lat),
                            lng: parseFloat(data.lng),
                            nombre: selectElement.options[selectElement.selectedIndex].text,
                            idDestino: idDestino,
                        };
                    } else {
                        delete puntosRuta[ordenReal];
                    }

                    actualizarMapa();
                });
        }

        async function actualizarMapa() {
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            if (!directionsService) directionsService = new google.maps.DirectionsService();
            if (!directionsRenderer) {
                directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#4285F4',
                        strokeOpacity: 1,
                        strokeWeight: 6
                    }
                });
                directionsRenderer.setMap(mapaRuta);
            }

            limpiarMarcadores();

            const ordenes = Object.keys(puntosRuta).map(Number).sort((a, b) => a - b);

            if (ordenes.length === 0) {
                directionsRenderer.set('directions', null);
                return;
            }

            if (ordenes.length === 1) {
                directionsRenderer.set('directions', null);
                const punto = puntosRuta[ordenes[0]];
                agregarMarcador(ordenes[0], punto, AdvancedMarkerElement);
                mapaRuta.setCenter({
                    lat: punto.lat,
                    lng: punto.lng
                });
                mapaRuta.setZoom(13);
                return;
            }

            const origen = puntosRuta[ordenes[0]];
            const destino = puntosRuta[ordenes[ordenes.length - 1]];
            const waypoints = ordenes.slice(1, -1).map(orden => ({
                location: new google.maps.LatLng(puntosRuta[orden].lat, puntosRuta[orden].lng),
                stopover: true,
            }));

            directionsService.route({
                origin: new google.maps.LatLng(origen.lat, origen.lng),
                destination: new google.maps.LatLng(destino.lat, destino.lng),
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
                optimizeWaypoints: false,
            }, (resultado, estado) => {
                if (estado === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(resultado);
                    ordenes.forEach(orden => agregarMarcador(orden, puntosRuta[orden], AdvancedMarkerElement));
                } else {
                    dibujarLineaRecta(ordenes);
                }
            });
        }

        function agregarMarcador(orden, punto, AdvancedMarkerElement) {
            const pin = document.createElement('div');
            pin.style.cssText = 'position:relative; display:flex; flex-direction:column; align-items:center;';
            const circulo = document.createElement('div');
            circulo.style.cssText = `
        background:#1F6B4B; color:white; border-radius:50%;
        width:34px; height:34px; display:flex; align-items:center;
        justify-content:center; font-weight:bold; font-size:13px;
        border:2px solid white; box-shadow:0 2px 6px rgba(0,0,0,.4);`;
            circulo.textContent = String(orden);
            const flecha = document.createElement('div');
            flecha.style.cssText = `
        width:0; height:0;
        border-left:7px solid transparent; border-right:7px solid transparent;
        border-top:10px solid #1F6B4B; margin-top:-2px;`;
            pin.appendChild(circulo);
            pin.appendChild(flecha);
            const marcador = new AdvancedMarkerElement({
                position: {
                    lat: punto.lat,
                    lng: punto.lng
                },
                map: mapaRuta,
                title: punto.nombre,
                content: pin,
            });
            marcadores.push(marcador);
        }

        async function dibujarLineaRecta(ordenes) {
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");
            const coords = [];
            ordenes.forEach(orden => {
                const punto = puntosRuta[orden];
                coords.push({
                    lat: punto.lat,
                    lng: punto.lng
                });
                agregarMarcador(orden, punto, AdvancedMarkerElement);
            });
            lineaRuta = new google.maps.Polyline({
                path: coords,
                strokeColor: '#1F6B4B',
                strokeOpacity: 0.85,
                strokeWeight: 4,
                map: mapaRuta,
            });
            const bounds = new google.maps.LatLngBounds();
            coords.forEach(c => bounds.extend(c));
            mapaRuta.fitBounds(bounds);
        }

        function limpiarMarcadores() {
            marcadores.forEach(m => m.map = null);
            marcadores = [];
        }

        async function initMapa() {
            const {
                Map
            } = await google.maps.importLibrary("maps");
            mapaRuta = new Map(document.getElementById('mapa-ruta'), {
                center: {
                    lat: 16.75,
                    lng: -92.64
                },
                zoom: 8,
                mapId: "DEMO_MAP_ID",
            });
        }
        initMapa();

        // Imágenes
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
