@extends('layouts.admin')

@section('title', 'Editar Ruta')

@section('content')
<div class="mb-4">
    <a href="{{ route('rutas.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">
        ← Regresar a mis rutas
    </a>
    <h1 class="ea-page-title mt-3 mb-1">Editar Ruta</h1>
    <p class="ea-subtitle mb-0">Modifica la información de tu ruta turística, sus destinos y actividades.</p>
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

<form action="{{ route('rutas.update', $ruta->id_ruta) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- BLOQUE 1: Información general --}}
    <div class="ea-card p-0 overflow-hidden mb-4">
        <div class="p-4 border-bottom" style="background: rgba(255,255,255,.25);">
            <div class="fw-semibold"><i class="bi bi-signpost-2 me-2"></i>Información General</div>
        </div>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold">Nombre de la ruta <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre', $ruta->nombre) }}" class="form-control rounded-3 py-2" required maxlength="120">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Descripción <span class="text-danger">*</span></label>
                    <textarea name="descripcion" rows="4" class="form-control rounded-3 py-2" required>{{ old('descripcion', $ruta->descripcion) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Dificultad <span class="text-danger">*</span></label>
                    <select name="dificultad" class="form-select" required>
                        <option value="baja" {{ old('dificultad', $ruta->dificultad) == 'baja' ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ old('dificultad', $ruta->dificultad) == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta" {{ old('dificultad', $ruta->dificultad) == 'alta' ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Duración estimada</label>
                    <input type="text" name="duracion_estimada" value="{{ old('duracion_estimada', $ruta->duracion_estimada) }}" class="form-control rounded-3 py-2" placeholder="Ej. 1 día, 6 horas">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Distancia (km)</label>
                    <input type="number" step="0.01" name="distancia_km" value="{{ old('distancia_km', $ruta->distancia_km) }}" class="form-control rounded-3 py-2" placeholder="Ej. 45.5">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha inicio operación</label>
                    <input type="date" name="fecha_inicio_operacion" value="{{ old('fecha_inicio_operacion', $ruta->fecha_inicio_operacion) }}" class="form-control rounded-3 py-2">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha fin operación</label>
                    <input type="date" name="fecha_fin_operacion" value="{{ old('fecha_fin_operacion', $ruta->fecha_fin_operacion) }}" class="form-control rounded-3 py-2">
                </div>
            </div>
        </div>
    </div>

    {{-- BLOQUE 2: Recomendaciones --}}
    <div class="ea-card p-0 overflow-hidden mb-4">
        <div class="p-4 border-bottom">
            <div class="fw-semibold"><i class="bi bi-lightbulb me-2"></i>Recomendaciones</div>
        </div>
        <div class="p-4">
            @if ($recomendacionesDisponibles->count() > 0)
                <div class="row g-2">
                    @foreach ($recomendacionesDisponibles as $rec)
                        <div class="col-12 col-md-4">
                            <label class="d-flex align-items-center gap-2 p-3 rounded-3 border" style="cursor:pointer; background:#f7f9f7;">
                                <input type="checkbox" name="recomendaciones[]" value="{{ $rec->id_recomendacion }}"
                                    class="form-check-input flex-shrink-0"
                                    {{ in_array($rec->id_recomendacion, old('recomendaciones', $recomendacionesSeleccionadas)) ? 'checked' : '' }}>
                                <div>
                                    <span class="fw-semibold small">{{ $rec->descripcion }}</span>
                                    <div><span class="badge bg-light text-dark border" style="font-size:.7rem;">{{ ucfirst($rec->tipo) }}</span></div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted small text-center py-3">Aún no hay recomendaciones registradas.</div>
            @endif
        </div>
    </div>

    {{-- BLOQUE 3: Destinos de la ruta (dinámico) --}}
    <div class="ea-card p-0 overflow-hidden mb-4">
        <div class="p-4 border-bottom">
            <div class="fw-semibold"><i class="bi bi-geo-alt me-2"></i>Destinos de la ruta</div>
        </div>
        <div class="p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="p-2 px-3 rounded-3 fw-semibold small" style="background:#f0f7f4; border:1px solid var(--ea-green);">
                    <i class="bi bi-geo-alt me-1"></i>
                    <span id="contador-paradas">{{ count($destinos) }}</span> Destino(s)
                </div>
                <button type="button" class="btn ea-btn-green rounded-3" onclick="agregarParada()">
                    <i class="bi bi-plus-lg me-1"></i> Agregar Destino
                </button>
            </div>

            <div id="contenedor-paradas">
                @foreach ($destinos as $index => $parada)
                    @php $i = $index + 1; @endphp
                    <div class="ea-card p-0 overflow-hidden mb-3 border" id="bloque-{{ $i }}">
                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,.15);">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill text-white fw-semibold num-parada" style="background: var(--ea-green); min-width: 28px;">{{ $i }}</span>
                                <span class="fw-semibold small">Parada {{ $i }}</span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" onclick="moverArriba({{ $i }})" title="Mover arriba">
                                    <i class="bi bi-arrow-up-short"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" onclick="moverAbajo({{ $i }})" title="Mover abajo">
                                    <i class="bi bi-arrow-down-short"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-3" onclick="eliminarParada({{ $i }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            <label class="form-label fw-bold small">Destino <span class="text-danger">*</span></label>
                            <select name="destinos[]" data-bloque="{{ $i }}" data-destino-id="{{ $parada->id_destino }}" class="form-select rounded-3 py-2 mb-2" onchange="cargarInfoDestino(this)">
                                <option value="">-- Selecciona un destino --</option>
                                @foreach ($todosDestinos as $destinoOption)
                                    <option value="{{ $destinoOption->id_destino }}" {{ $parada->id_destino == $destinoOption->id_destino ? 'selected' : '' }}>{{ $destinoOption->nombre }}</option>
                                @endforeach
                            </select>
                            <div id="info-parada-{{ $i }}">
                                <div id="desc-parada-{{ $i }}" class="p-3 rounded-3 small mb-3" style="background:#f0f7f4; border-left:3px solid var(--ea-green);">
                                    {{ $parada->descripcion }}
                                </div>
                                <label class="form-label fw-bold small mb-2">Actividades disponibles</label>
                                <div id="actividades-parada-{{ $i }}" class="row g-2"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- BLOQUE 4: Mapa de la ruta --}}
    <div class="ea-card p-0 overflow-hidden mb-4">
        <div class="p-4 border-bottom">
            <div class="fw-semibold"><i class="bi bi-map me-2"></i>Mapa de la ruta</div>
        </div>
        <div class="p-4">
            <div id="mapa-edicion" class="rounded-3" style="height: 400px; width: 100%; background: #f0f0f0;"></div>
            <p class="text-muted small mt-2">
                <i class="bi bi-info-circle me-1"></i>
                Los marcadores muestran el orden de las paradas. La ruta se traza automáticamente.
            </p>
        </div>
    </div>

    {{-- BLOQUE 5: Imágenes actuales --}}
    @if ($imagenes->count() > 0)
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom">
                <div class="fw-semibold"><i class="bi bi-images me-2"></i>Imágenes actuales</div>
            </div>
            <div class="p-4">
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($imagenes as $imagen)
                        <div class="position-relative" id="img-{{ $imagen->id_imagen }}">
                            <img src="{{ Storage::url($imagen->ruta_archivo) }}" class="rounded-3 border" style="width:120px; height:90px; object-fit:cover;">
                            <button type="button" onclick="eliminarImagen({{ $imagen->id_imagen }})" class="btn btn-danger btn-sm rounded-circle position-absolute" style="top:-8px; right:-8px; width:24px; height:24px; padding:0; font-size:.7rem;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- BLOQUE 6: Agregar nuevas imágenes --}}
    <div class="ea-card p-0 overflow-hidden mb-4">
        <div class="p-4 border-bottom">
            <div class="fw-semibold"><i class="bi bi-cloud-arrow-up me-2"></i>Agregar nuevas imágenes</div>
        </div>
        <div class="p-4">
            <input type="file" id="fotosInput" name="fotos[]" accept="image/png,image/jpeg" class="d-none" multiple>
            <div id="dropzone" class="rounded-3 border border-2 border-dashed p-5 text-center" style="border-color: #cdd8cd !important; background: #f7f9f7; cursor:pointer;" onclick="document.getElementById('fotosInput').click()">
                <div class="mx-auto mb-3 rounded-3 d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e2ece9;">
                    <i class="bi bi-cloud-arrow-up text-success fs-4"></i>
                </div>
                <div class="fw-semibold text-dark">Haz clic para subir o arrastra imágenes</div>
                <div class="small text-muted mt-1">PNG, JPG hasta 5MB cada una</div>
            </div>
            <div id="previews" class="d-flex flex-wrap gap-2 mt-3"></div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
        <a href="{{ route('rutas.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">Cancelar</a>
        <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
            <i class="bi bi-floppy me-1"></i> Guardar cambios
        </button>
    </div>
</form>

@push('scripts')
<script>
    // ==================================================
    // 1. VARIABLES GLOBALES
    // ==================================================
    let contadorParadas = {{ count($destinos) }};
    let opcionesDestinos = @json($todosDestinos->map(fn($d) => ['id' => $d->id_destino, 'nombre' => $d->nombre]));
    let mapaRuta, directionsService, directionsRenderer, marcadores = [], infoWindow;

    // ==================================================
    // 2. FUNCIONES PARA DESTINOS
    // ==================================================
    function agregarParada() {
        contadorParadas++;
        const id = contadorParadas;
        let options = '<option value="">-- Selecciona un destino --</option>';
        opcionesDestinos.forEach(d => {
            options += `<option value="${d.id}">${d.nombre}</option>`;
        });
        const html = `
        <div class="ea-card p-0 overflow-hidden mb-3 border" id="bloque-${id}">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,.15);">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-white fw-semibold num-parada" style="background: var(--ea-green); min-width: 28px;">${id}</span>
                    <span class="fw-semibold small">Parada ${id}</span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" onclick="moverArriba(${id})"><i class="bi bi-arrow-up-short"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 me-2" onclick="moverAbajo(${id})"><i class="bi bi-arrow-down-short"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3" onclick="eliminarParada(${id})"><i class="bi bi-trash"></i></button>
                </div>
            </div>
            <div class="p-3">
                <label class="form-label fw-bold small">Destino <span class="text-danger">*</span></label>
                <select name="destinos[]" data-bloque="${id}" class="form-select rounded-3 py-2 mb-2" onchange="cargarInfoDestino(this)">
                    ${options}
                </select>
                <div id="info-parada-${id}" style="display:none;">
                    <div id="desc-parada-${id}" class="p-3 rounded-3 small mb-3" style="background:#f0f7f4; border-left:3px solid var(--ea-green);"></div>
                    <label class="form-label fw-bold small mb-2">Actividades disponibles</label>
                    <div id="actividades-parada-${id}" class="row g-2"></div>
                </div>
            </div>
        </div>`;
        document.getElementById('contenedor-paradas').insertAdjacentHTML('beforeend', html);
        actualizarNumeros();
    }

    function eliminarParada(id) {
        document.getElementById(`bloque-${id}`).remove();
        actualizarNumeros();
        actualizarMapa();
    }

    function moverArriba(id) {
        const bloque = document.getElementById(`bloque-${id}`);
        const anterior = bloque.previousElementSibling;
        if (anterior) {
            bloque.parentNode.insertBefore(bloque, anterior);
            actualizarNumeros();
            actualizarMapa();
        }
    }

    function moverAbajo(id) {
        const bloque = document.getElementById(`bloque-${id}`);
        const siguiente = bloque.nextElementSibling;
        if (siguiente) {
            bloque.parentNode.insertBefore(siguiente, bloque);
            actualizarNumeros();
            actualizarMapa();
        }
    }

    function actualizarNumeros() {
        const bloques = document.querySelectorAll('#contenedor-paradas > div[id^="bloque-"]');
        bloques.forEach((bloque, idx) => {
            const numero = idx + 1;
            bloque.querySelector('.num-parada').textContent = numero;
            const select = bloque.querySelector('select');
            if (select) select.dataset.bloque = numero;
        });
        document.getElementById('contador-paradas').textContent = bloques.length;
    }

    // ==================================================
    // 3. CARGAR INFO DESTINO
    // ==================================================
    async function cargarInfoDestino(selectElement) {
        const idDestino = selectElement.value;
        const idBloque = selectElement.dataset.bloque;
        const infoDiv = document.getElementById(`info-parada-${idBloque}`);
        const descDiv = document.getElementById(`desc-parada-${idBloque}`);
        const actividadesDiv = document.getElementById(`actividades-parada-${idBloque}`);

        if (!idDestino) {
            if (infoDiv) infoDiv.style.display = 'none';
            return;
        }

        infoDiv.style.display = 'block';
        const response = await fetch(`/api/destino-info/${idDestino}`);
        const data = await response.json();
        descDiv.innerHTML = data.descripcion;

        if (data.actividades && data.actividades.length) {
            actividadesDiv.innerHTML = '';
            data.actividades.forEach(act => {
                const label = document.createElement('label');
                label.className = 'col-12 col-md-4 d-flex align-items-center gap-2 p-3 rounded-3 border';
                label.style.cssText = 'cursor:pointer; background:#f7f9f7;';
                label.innerHTML = `
                    <input type="checkbox" name="actividades_${idDestino}[]" value="${act.id_actividad}" class="form-check-input flex-shrink-0">
                    <span class="fw-semibold small">${act.nombre}</span>
                `;
                actividadesDiv.appendChild(label);
            });
        } else {
            actividadesDiv.innerHTML = '<div class="col-12 text-muted small">Este destino no tiene actividades registradas.</div>';
        }
        actualizarMapa();
    }

    async function cargarActividadesExistentes() {
        const selects = document.querySelectorAll('select[data-bloque]');
        for (let select of selects) {
            const idDestino = select.value;
            const idBloque = select.dataset.bloque;
            if (idDestino) {
                const response = await fetch(`/api/destino-info/${idDestino}`);
                const data = await response.json();
                const descDiv = document.getElementById(`desc-parada-${idBloque}`);
                const actividadesDiv = document.getElementById(`actividades-parada-${idBloque}`);
                if (descDiv) descDiv.innerHTML = data.descripcion;
                if (actividadesDiv && data.actividades) {
                    actividadesDiv.innerHTML = '';
                    data.actividades.forEach(act => {
                        const label = document.createElement('label');
                        label.className = 'col-12 col-md-4 d-flex align-items-center gap-2 p-3 rounded-3 border';
                        label.style.cssText = 'cursor:pointer; background:#f7f9f7;';
                        label.innerHTML = `
                            <input type="checkbox" name="actividades_${idDestino}[]" value="${act.id_actividad}" class="form-check-input flex-shrink-0">
                            <span class="fw-semibold small">${act.nombre}</span>
                        `;
                        actividadesDiv.appendChild(label);
                    });
                }
            }
        }
    }

    // ==================================================
    // 4. MAPA (Directions API + marcadores numerados)
    // ==================================================
    async function actualizarMapa() {
        if (!mapaRuta) {
            console.warn('Mapa no inicializado aún');
            return;
        }

        const bloques = document.querySelectorAll('#contenedor-paradas > div[id^="bloque-"]');
        const destinosMapa = [];
        for (let bloque of bloques) {
            const select = bloque.querySelector('select');
            const idDestino = select ? select.value : null;
            if (!idDestino) continue;
            const nombre = select.options[select.selectedIndex].text;
            try {
                const res = await fetch(`/api/destino-info/${idDestino}`);
                const data = await res.json();
                if (data.lat && data.lng) {
                    destinosMapa.push({
                        id: idDestino,
                        nombre: nombre,
                        lat: parseFloat(data.lat),
                        lng: parseFloat(data.lng),
                        orden: destinosMapa.length + 1
                    });
                }
            } catch (err) { console.error(err); }
        }

        // Limpiar capas anteriores
        if (directionsRenderer) directionsRenderer.setMap(null);
        marcadores.forEach(m => m.map = null);
        marcadores = [];

        if (destinosMapa.length === 0) return;

        const bounds = new google.maps.LatLngBounds();
        destinosMapa.forEach(d => {
            const pos = { lat: d.lat, lng: d.lng };
            bounds.extend(pos);
            const marker = crearMarcadorNumerado(d.orden, pos, d.nombre);
            marcadores.push(marker);
        });
        mapaRuta.fitBounds(bounds);
        if (destinosMapa.length === 1) mapaRuta.setZoom(13);

        if (destinosMapa.length > 1) {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: { strokeColor: '#4285F4', strokeOpacity: 1, strokeWeight: 6 }
            });
            directionsRenderer.setMap(mapaRuta);
            const origen = destinosMapa[0];
            const destino = destinosMapa[destinosMapa.length - 1];
            const waypoints = destinosMapa.slice(1, -1).map(p => ({
                location: new google.maps.LatLng(p.lat, p.lng),
                stopover: true,
            }));
            directionsService.route({
                origin: new google.maps.LatLng(origen.lat, origen.lng),
                destination: new google.maps.LatLng(destino.lat, destino.lng),
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
                optimizeWaypoints: false,
            }, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.warn('No se pudo trazar la ruta:', status);
                }
            });
        }
    }

    function crearMarcadorNumerado(orden, posicion, titulo) {
        const pin = document.createElement('div');
        pin.style.cssText = 'position:relative; display:flex; flex-direction:column; align-items:center;';
        const circulo = document.createElement('div');
        circulo.style.cssText = `
            background:#1F6B4B; color:white; border-radius:50%;
            width:34px; height:34px; display:flex; align-items:center;
            justify-content:center; font-weight:bold; font-size:13px;
            border:2px solid white; box-shadow:0 2px 6px rgba(0,0,0,.4);
        `;
        circulo.textContent = orden.toString();
        const flecha = document.createElement('div');
        flecha.style.cssText = `
            width:0; height:0;
            border-left:7px solid transparent; border-right:7px solid transparent;
            border-top:10px solid #1F6B4B; margin-top:-2px;
        `;
        pin.appendChild(circulo);
        pin.appendChild(flecha);
        const marker = new google.maps.marker.AdvancedMarkerElement({
            position: posicion,
            map: mapaRuta,
            title: titulo,
            content: pin,
        });
        marker.addListener('click', () => {
            infoWindow.setContent(`
                <div style="padding:6px;">
                    <strong style="color:#1F6B4B;">${titulo}</strong><br>
                    <span style="font-size:11px;">Parada ${orden}</span><br>
                    <a href="https://www.google.com/maps/search/?api=1&query=${posicion.lat},${posicion.lng}" target="_blank">Ver en Google Maps →</a>
                </div>
            `);
            infoWindow.open({ anchor: marker, map: mapaRuta });
        });
        return marker;
    }

    async function initMapaEdicion() {
        const contenedor = document.getElementById('mapa-edicion');
        if (!contenedor) {
            console.error('No se encontró el contenedor #mapa-edicion');
            return;
        }

        // Esperar a que la API de Google Maps esté disponible
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            console.error('Google Maps no cargado');
            contenedor.innerHTML = '<div class="alert alert-danger">Error cargando Google Maps. Revisa tu clave de API.</div>';
            return;
        }

        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        mapaRuta = new Map(contenedor, {
            center: { lat: 16.75, lng: -92.64 },
            zoom: 8,
            mapId: "DEMO_MAP_ID",
        });
        infoWindow = new google.maps.InfoWindow();

        // Una vez inicializado, actualizar el mapa con los destinos actuales
        await actualizarMapa();
    }

    // ==================================================
    // 5. IMÁGENES
    // ==================================================
    async function eliminarImagen(id) {
        if (!confirm('¿Eliminar esta imagen?')) return;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const response = await fetch(`/rutas/imagen/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            if (response.ok && data.ok === true) {
                const imgElement = document.getElementById(`img-${id}`);
                if (imgElement) imgElement.remove();
            } else {
                alert('Error al eliminar: ' + (data.error || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error de red:', error);
            alert('No se pudo conectar con el servidor.');
        }
    }

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
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = '#1F6B4B'; });
    dropzone.addEventListener('dragleave', () => dropzone.style.borderColor = '#cdd8cd');
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.style.borderColor = '#cdd8cd';
        fotosInput.files = e.dataTransfer.files;
        mostrarPreviews(e.dataTransfer.files);
    });

    // ==================================================
    // 6. INICIALIZACIÓN
    // ==================================================
    document.addEventListener('DOMContentLoaded', async function() {
        await cargarActividadesExistentes();
        actualizarNumeros();

        // Cargar Google Maps si aún no está disponible
        if (typeof google !== 'undefined' && google.maps) {
            await initMapaEdicion();
        } else {
            window.initMapCallback = initMapaEdicion;
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMapCallback`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        // Observar cambios en selects para actualizar el mapa
        document.getElementById('contenedor-paradas').addEventListener('change', (e) => {
            if (e.target && e.target.tagName === 'SELECT') actualizarMapa();
        });
    });
</script>
@endpush
@endsection