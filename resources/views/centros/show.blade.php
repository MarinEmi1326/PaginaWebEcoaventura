@extends('layouts.app')

@section('content')

    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="py-3">
        <div class="container">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-success text-decoration-none">Inicio</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('destinos.index') }}"
                        class="text-success text-decoration-none">Centros Turísticos</a></li>
                <li class="breadcrumb-item active">{{ $destino->nombre }}</li>
            </ol>
        </div>
    </nav>

    {{-- CARRUSEL --}}
    <section class="pb-4">
        <div class="container">
            @if ($imagenes->count() > 0)
                <div id="carruselDestino" class="carousel slide rounded-4 overflow-hidden shadow-sm"
                    data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($imagenes as $i => $img)
                            <button type="button" data-bs-target="#carruselDestino" data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner" style="height: 420px;">
                        @foreach ($imagenes as $i => $img)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }} h-100">
                                <img src="{{ Storage::url($img->ruta_archivo) }}"
                                    class="d-block w-100 h-100 object-fit-cover" alt="{{ $destino->nombre }}">
                            </div>
                        @endforeach
                    </div>
                    @if ($imagenes->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carruselDestino"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carruselDestino"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <div class="rounded-4 d-flex align-items-center justify-content-center"
                    style="height:300px; background:#e2ece9;">
                    <i class="bi bi-image text-muted fs-1"></i>
                </div>
            @endif
        </div>
    </section>

    {{-- CONTENIDO --}}
    <section class="pb-5">
        <div class="container">

            {{-- Categorías + Título --}}
            <div class="mb-4">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    @foreach ($categorias as $cat)
                        <span class="badge rounded-pill px-3 py-2"
                            style="background:#e2ece9; color:#1F6B4B; font-weight:500;">
                            <i class="bi bi-tag me-1"></i>{{ $cat }}
                        </span>
                    @endforeach
                    <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Ocosingo, Chiapas</span>
                </div>
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                    <div>
                        <h1 class="fw-bold display-5 mb-0">{{ $destino->nombre }}</h1>
                        @if ($destino->telefono)
                            <p class="text-muted mt-2 mb-0"><i
                                    class="bi bi-telephone me-1 text-success"></i>{{ $destino->telefono }}</p>
                        @endif
                        @if ($creador)
                            <p class="text-muted mt-2 mb-0 small">
                                <i class="bi bi-person-circle me-1 text-success"></i>
                                Creado por: {{ $creador->nombre }} {{ $creador->apellidos }}
                            </p>
                        @endif
                    </div>
                    @auth
                        @if ($usuarioRol === 'turista')
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3 mt-2" data-bs-toggle="modal"
                                data-bs-target="#modalReportarDestino">
                                <i class="bi bi-flag me-1"></i> Reportar destino
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- TABS --}}
            <div class="rounded-3 p-1 mb-4" style="background:#e8ede9;">
                <ul class="nav" id="detalleTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-descripcion"
                            type="button">Descripción</button>
                    </li>
                    @if ($actividades->count() > 0)
                        <li class="nav-item">
                            <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-actividades"
                                type="button">
                                Actividades
                                <span class="badge rounded-pill ms-1"
                                    style="background:#1F6B4B; font-size:.7rem;">{{ $actividades->count() }}</span>
                            </button>
                        </li>
                    @endif
                    @if ($paquetes->count() > 0)
                        <li class="nav-item">
                            <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-paquetes"
                                type="button">
                                Paquetes
                                <span class="badge rounded-pill ms-1"
                                    style="background:#1F6B4B; font-size:.7rem;">{{ $paquetes->count() }}</span>
                            </button>
                        </li>
                    @endif
                    @if (count($recomendaciones) > 0)
                        <li class="nav-item">
                            <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-recomendaciones"
                                type="button">Recomendaciones</button>
                        </li>
                    @endif
                    @if ($destino->lat && $destino->lng)
                        <li class="nav-item">
                            <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-mapa"
                                type="button">
                                <i class="bi bi-map me-1"></i>Ubicación
                            </button>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="tab-content mb-5" id="detalleTabContent">

                {{-- DESCRIPCIÓN --}}
                <div class="tab-pane fade show active" id="tab-descripcion" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <p class="lh-lg mb-0">{{ $destino->descripcion }}</p>
                        </div>
                    </div>
                </div>

                {{-- ACTIVIDADES --}}
                @if ($actividades->count() > 0)
                    <div class="tab-pane fade" id="tab-actividades" role="tabpanel">
                        <div class="row g-4">
                            @foreach ($actividades as $act)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold mb-3">{{ $act->nombre }}</h5>
                                            <p class="card-text text-muted small mb-0">Actividad disponible en este
                                                destino.</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- PAQUETES --}}
                @if ($paquetes->count() > 0)
                    <div class="tab-pane fade" id="tab-paquetes" role="tabpanel">
                        <div class="row g-4">
                            @foreach ($paquetes as $paq)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold mb-3">{{ $paq->nombre }}</h5>
                                            @if ($paq->descripcion)
                                                <p class="card-text text-muted small">{{ $paq->descripcion }}</p>
                                            @endif
                                            <div class="d-flex flex-wrap justify-content-between align-items-center my-3">
                                                <div>
                                                    <span
                                                        class="fw-bold text-success fs-4">${{ number_format($paq->precio, 2) }}
                                                        MXN</span>
                                                    <span class="text-muted small ms-1">(por persona)</span>
                                                </div>
                                                @if ($paq->tipo_publico == 'especifico')
                                                    <span class="ea-chip blue">Edad {{ $paq->edad_minima }} -
                                                        {{ $paq->edad_maxima }} años</span>
                                                @else
                                                    <span class="ea-chip green">Todo público</span>
                                                @endif
                                            </div>
                                            @if ($paq->actividades->count() > 0)
                                                <hr>
                                                <div class="small">
                                                    <strong><i class="bi bi-list-check me-1"></i>Actividades
                                                        incluidas:</strong>
                                                    <ul class="mt-2 ps-3">
                                                        @foreach ($paq->actividades as $actPaq)
                                                            <li>
                                                                {{ $actPaq->nombre }}
                                                                <span class="text-muted">({{ $actPaq->minimo_personas }} -
                                                                    {{ $actPaq->maximo_personas }} pers.)</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="mt-3">
                                                @auth
                                                    @if ($usuarioRol === 'turista' && $paq->precio)
                                                        <a href="{{ route('pagos.show', $paq->id_paquete) }}"
                                                            class="btn btn-success rounded-3 w-100">
                                                            <i class="bi bi-credit-card me-1"></i>Adquirir paquete
                                                        </a>
                                                    @endif
                                                @else
                                                    @if ($paq->precio)
                                                        <a href="{{ route('login') }}"
                                                            class="btn btn-outline-success rounded-3 w-100">
                                                            <i class="bi bi-person me-1"></i>Inicia sesión para adquirir
                                                        </a>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- RECOMENDACIONES --}}
                @if (count($recomendaciones) > 0)
                    <div class="tab-pane fade" id="tab-recomendaciones" role="tabpanel">
                        <div class="row g-3">
                            @foreach ($recomendaciones as $rec)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <div class="card-body d-flex align-items-center gap-2">
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                            <span class="small">{{ $rec }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- MAPA --}}
                @if ($destino->lat && $destino->lng)
                    <div class="tab-pane fade" id="tab-mapa" role="tabpanel">
                        <div class="rounded-4 overflow-hidden" style="height: 380px;">
                            <div id="mapa-destino" class="rounded-3 mb-3" style="height: 350px; width: 100%;"></div>
                        </div>
                        <p class="small text-muted mt-2">
                            <i class="bi bi-geo-alt me-1 text-success"></i>
                            {{ $destino->lat }}, {{ $destino->lng }}
                        </p>
                    </div>
                @endif

                {{-- BOTÓN DE COMENTAR --}}
                <div class="d-flex flex-wrap gap-3 mb-5 pt-3 border-top">
                    @auth
                        @if ($usuarioRol === 'turista')
                            <a href="#comentarios" class="btn btn-success rounded-3 px-4 py-2">
                                <i class="bi bi-chat me-2"></i>Dejar un comentario
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success rounded-3 px-4 py-2">
                            <i class="bi bi-person me-2"></i>Inicia sesión para comentar
                        </a>
                    @endauth
                </div>

                {{-- COMENTARIOS --}}
                <div class="mb-5" id="comentarios">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-chat-square-text me-2 text-success"></i>
                        Comentarios
                        <span class="badge rounded-pill ms-1"
                            style="background:#e2ece9; color:#1F6B4B; font-size:.8rem;">{{ $comentarios->count() }}</span>
                    </h5>

                    @if (session('success'))
                        <div class="alert alert-success rounded-3 mb-3" id="alerta-com">{{ session('success') }}
                        </div>
                        <script>
                            setTimeout(() => {
                                const a = document.getElementById('alerta-com');
                                if (a) {
                                    a.style.transition = 'opacity .5s';
                                    a.style.opacity = '0';
                                    setTimeout(() => a.remove(), 500);
                                }
                            }, 3000);
                        </script>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
                    @endif

                    @auth
                        @if ($usuarioRol === 'turista')
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-chat-dots me-2 text-success"></i>Tu comentario
                                    </h6>
                                    <form action="{{ route('comentarios.destino.store', $destino->id_destino) }}"
                                        method="POST">
                                        @csrf
                                        <textarea name="comentario" rows="3"
                                            class="form-control rounded-3 mb-3 @error('comentario') is-invalid @enderror"
                                            placeholder="Comparte tu experiencia en este destino...">{{ old('comentario') }}</textarea>
                                        @error('comentario')
                                            <div class="invalid-feedback mb-2">{{ $message }}</div>
                                        @enderror
                                        <button type="submit" class="btn btn-success rounded-3 px-4">
                                            <i class="bi bi-send me-1"></i> Publicar comentario
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
                            <div class="card-body p-4">
                                <i class="bi bi-chat fs-1 text-muted mb-2 d-block"></i>
                                <p class="text-muted small mb-2">Inicia sesión como Turista para comentar</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">
                                    <i class="bi bi-person me-1"></i>Iniciar Sesión
                                </a>
                            </div>
                        </div>
                    @endauth

                    @forelse ($comentarios as $com)
                        {{-- TARJETA DE COMENTARIO --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width:40px; height:40px; background:#1F6B4B; color:white;">
                                            {{ strtoupper(substr($com->nombre, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="fw-semibold mb-0 small">{{ $com->nombre }} {{ $com->apellidos }}
                                            </p>
                                            <p class="text-muted mb-0" style="font-size:0.7rem;">
                                                {{ \Carbon\Carbon::parse($com->fecha)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @auth
                                            @if (in_array($usuarioRol, ['turista', 'admin_destinos']))
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalReportarComentario{{ $com->id_comentario }}">
                                                    <i class="bi bi-flag"></i>
                                                </button>
                                            @endif
                                            @if ($usuarioRol === 'admin_general')
                                                <form action="{{ route('comentarios.destroy', $com->id_comentario) }}"
                                                    method="POST" onsubmit="return confirm('¿Eliminar este comentario?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <p class="text-muted small mb-0 mt-2">{{ $com->comentario }}</p>
                            </div>
                        </div>

                        {{-- MODAL REPORTAR COMENTARIO --}}
                        @auth
                            @if (in_array($usuarioRol, ['turista', 'admin_destinos']))
                                <div class="modal fade" id="modalReportarComentario{{ $com->id_comentario }}"
                                    tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold"><i
                                                        class="bi bi-flag-fill text-danger me-2"></i>Reportar comentario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('reportes.comentario', $com->id_comentario) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body pt-2">
                                                    <blockquote class="rounded-3 p-3 mb-3 small fst-italic"
                                                        style="background:#f7f9f7; border-left:3px solid #dc3545;">
                                                        {{ Str::limit($com->comentario, 100) }}
                                                    </blockquote>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Motivo <span
                                                                class="text-danger">*</span></label>
                                                        <select name="motivo" class="form-select rounded-3" required>
                                                            <option value="" disabled selected>Selecciona un motivo
                                                            </option>
                                                            <option value="contenido_inapropiado">Contenido inapropiado
                                                            </option>
                                                            <option value="informacion_falsa">Información falsa</option>
                                                            <option value="spam">Spam</option>
                                                            <option value="lenguaje_ofensivo">Lenguaje ofensivo</option>
                                                            <option value="derechos_autor">Derechos de autor</option>
                                                            <option value="otro">Otro</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label fw-bold small">Descripción <span
                                                                class="text-muted fw-normal">(opcional)</span></label>
                                                        <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Describe el problema..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-3"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger rounded-3 px-4"><i
                                                            class="bi bi-flag me-1"></i>Enviar reporte</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-chat fs-2 d-block mb-2"></i>
                            Sé el primero en comentar este destino.
                        </div>
                    @endforelse
                </div>

                {{-- OTROS DESTINOS --}}
                @if ($otrosDestinos->count() > 0)
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4">Otros destinos que podrían interesarte</h5>
                        <div class="row g-4">
                            @foreach ($otrosDestinos as $od)
                                <div class="col-md-6 col-lg-4">
                                    @include('centros._card', ['d' => $od])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
    </section>

    {{-- MODAL REPORTAR DESTINO --}}
    @auth
        @if ($usuarioRol === 'turista')
            <div class="modal fade" id="modalReportarDestino" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold"><i class="bi bi-flag-fill text-danger me-2"></i>Reportar destino
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('reportes.destino', $destino->id_destino) }}" method="POST">
                            @csrf
                            <div class="modal-body pt-2">
                                <p class="text-muted small mb-3">Tu reporte será enviado al administrador para su revisión.</p>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Motivo <span class="text-danger">*</span></label>
                                    <select name="motivo" class="form-select rounded-3" required>
                                        <option value="" disabled selected>Selecciona un motivo</option>
                                        <option value="contenido_inapropiado">Contenido inapropiado</option>
                                        <option value="informacion_falsa">Información falsa</option>
                                        <option value="spam">Spam</option>
                                        <option value="lenguaje_ofensivo">Lenguaje ofensivo</option>
                                        <option value="derechos_autor">Derechos de autor</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold small">Descripción <span
                                            class="text-muted fw-normal">(opcional)</span></label>
                                    <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Describe el problema..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light rounded-3"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger rounded-3 px-4"><i
                                        class="bi bi-flag me-1"></i>Enviar reporte</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
@endpush

@push('scripts')
    <script>
        // Mapa con tarjeta de información que muestra dirección completa
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

        let mapaDestino, marcadorActivo, infoWindow;

        async function initMapaDestino() {
            const {
                Map
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            const lat = {{ $destino->lat }};
            const lng = {{ $destino->lng }};
            const nombre = "{{ $destino->nombre }}";

            mapaDestino = new Map(document.getElementById("mapa-destino"), {
                zoom: 12,
                center: {
                    lat: lat,
                    lng: lng
                },
                mapId: "DEMO_MAP_ID",
            });

            // Obtener dirección desde las coordenadas (geocodificación inversa)
            const geocoder = new google.maps.Geocoder();
            let direccion = "Dirección no disponible";

            try {
                const result = await geocoder.geocode({
                    location: {
                        lat: lat,
                        lng: lng
                    }
                });
                if (result.results && result.results[0]) {
                    direccion = result.results[0].formatted_address;
                }
            } catch (e) {
                console.log("Error al obtener dirección:", e);
            }

            // Crear tarjeta de información con dirección completa
            infoWindow = new google.maps.InfoWindow({
                content: `
                <div style="font-family: 'Inter', sans-serif; padding: 8px; max-width: 300px; min-width: 250px;">
                    <strong style="color: #1F6B4B; font-size: 14px;">${nombre}</strong><br>
                    <span style="font-size: 12px; color: #555; display: block; margin-top: 6px;">${direccion}</span>
                    <hr style="margin: 8px 0; border-color: #e2ece9;">
                    <span style="font-size: 11px; color: #888;">📌 ${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
                    <br>
                    <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" 
                       target="_blank" 
                       style="font-size: 11px; color: #1F6B4B; text-decoration: none; display: inline-block; margin-top: 5px;">
                        Ver en Google Maps →
                    </a>
                </div>
            `
            });

            // Crear marcador
            marcadorActivo = new AdvancedMarkerElement({
                map: mapaDestino,
                position: {
                    lat: lat,
                    lng: lng
                },
                title: nombre,
            });

            // Mostrar tarjeta al hacer clic
            marcadorActivo.addListener("click", () => {
                infoWindow.open({
                    anchor: marcadorActivo,
                    map: mapaDestino,
                });
            });
        }

        // Inicializar cuando se abre la pestaña
        document.querySelectorAll('[data-bs-target="#tab-mapa"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', function() {
                if (!mapaDestino) initMapaDestino();
            });
        });

        if (document.getElementById('tab-mapa')?.classList.contains('active')) {
            initMapaDestino();
        }
    </script>
@endpush
