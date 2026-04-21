@extends('layouts.app')

@section('content')

{{-- BREADCRUMB --}}
<nav aria-label="breadcrumb" class="py-3">
    <div class="container">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-success text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ruta') }}" class="text-success text-decoration-none">Rutas Turísticas</a></li>
            <li class="breadcrumb-item active">{{ $ruta->nombre }}</li>
        </ol>
    </div>
</nav>

{{-- ENCABEZADO --}}
<div class="container mb-4">
    <div class="mb-2">
        @if ($ruta->dificultad === 'baja')
            <span class="badge badge-facil">Fácil</span>
        @elseif ($ruta->dificultad === 'media')
            <span class="badge badge-moderada">Moderada</span>
        @else
            <span class="badge badge-alta">Alta</span>
        @endif
    </div>
    <h1 class="fw-bold mb-1">{{ $ruta->nombre }}</h1>
    <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
        @if ($ruta->duracion_estimada)
            <span><i class="bi bi-clock me-1"></i>{{ $ruta->duracion_estimada }}</span>
        @endif
        @if ($ruta->distancia_km)
            <span><i class="bi bi-geo-alt me-1"></i>{{ $ruta->distancia_km }} km</span>
        @endif
        <span><i class="bi bi-flag me-1"></i>{{ $destinos->count() }} parada(s)</span>
        @if ($ruta->fecha_inicio_operacion && $ruta->fecha_fin_operacion)
            <span><i class="bi bi-calendar-range me-1"></i>
                {{ \Carbon\Carbon::parse($ruta->fecha_inicio_operacion)->format('M') }}
                –
                {{ \Carbon\Carbon::parse($ruta->fecha_fin_operacion)->format('M') }}
            </span>
        @endif
    </div>
</div>

{{-- TABS --}}
<div class="container mb-4">
    <div class="rounded-3 p-1" style="background:#e8ede9;">
        <ul class="nav" id="detalleTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-descripcion" type="button">Descripción</button>
            </li>
            <li class="nav-item">
                <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-puntos" type="button">Puntos de Interés</button>
            </li>
            @if ($ruta->lat && $ruta->lng || $destinos->first()->lat)
                <li class="nav-item">
                    <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-mapa" type="button">
                        <i class="bi bi-map me-1"></i>Ubicación
                    </button>
                </li>
            @endif
            <li class="nav-item">
                <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-comentarios" type="button">Comentarios</button>
            </li>
        </ul>
    </div>
</div>

<div class="container mb-5">
    <div class="tab-content">

        {{-- TAB DESCRIPCIÓN --}}
        <div class="tab-pane fade show active" id="tab-descripcion" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <p class="lh-lg mb-0">{{ $ruta->descripcion }}</p>
                    @if (count($recomendaciones) > 0)
                        <div class="mt-4 p-3 rounded-3" style="background:#f0f7f4; border-left: 3px solid #1F6B4B;">
                            <div class="small fw-semibold mb-1" style="color:#1F6B4B;">
                                <i class="bi bi-lightbulb me-1"></i>Recomendaciones
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach ($recomendaciones as $rec)
                                    <span class="badge badge-eco" style="color: #1e2a22; background-color: #e2ece9;">{{ $rec }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB PUNTOS DE INTERÉS --}}
        <div class="tab-pane fade" id="tab-puntos" role="tabpanel">
            <div class="row g-4">
                @foreach ($destinos as $destino)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; background: #1F6B4B !important;">
                                        {{ $destino->orden }}
                                    </div>
                                    <h5 class="card-title fw-bold mb-0">{{ $destino->nombre }}</h5>
                                </div>
                                <p class="text-muted small">{{ Str::limit($destino->descripcion, 120) }}</p>
                                @if ($destino->actividades->count() > 0)
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach ($destino->actividades as $actividad)
                                            <span class="badge badge-eco">{{ $actividad->nombre }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TAB MAPA (con ruta trazada) --}}
        @if ($ruta->lat && $ruta->lng || $destinos->first()->lat)
            <div class="tab-pane fade" id="tab-mapa" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div id="mapa-ruta" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        @endif

        {{-- TAB COMENTARIOS --}}
        <div class="tab-pane fade" id="tab-comentarios" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Comentarios</h5>
                    @if (session('success'))
                        <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
                    @endif

                    @auth
                        @if (auth()->user()->rol === 'turista')
                            <div class="rounded-3 p-4 mb-4" style="background:#f1f5f2;">
                                <form action="{{ route('comentarios.ruta.store', $ruta->id_ruta) }}" method="POST">
                                    @csrf
                                    <label class="form-label fw-bold small">Tu comentario</label>
                                    <textarea name="comentario" rows="3" class="form-control rounded-3 mb-3" placeholder="Comparte tu experiencia..." required></textarea>
                                    <button type="submit" class="btn btn-success rounded-3 px-4">Publicar comentario</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="rounded-3 p-4 mb-4 text-center" style="background:#f1f5f2;">
                            <p class="text-muted small mb-2">Inicia sesión como Turista para comentar</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">Iniciar Sesión</a>
                        </div>
                    @endauth

                    @forelse ($comentarios as $comentario)
                        <div class="rounded-3 p-4 mb-3" style="background:#f1f5f2;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;background:#d1d9d4;color:#1F6B4B;">
                                        {{ strtoupper(substr($comentario->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="fw-semibold mb-0 small">{{ $comentario->nombre }} {{ $comentario->apellidos }}</p>
                                        <p class="text-muted mb-0" style="font-size:.75rem;">{{ \Carbon\Carbon::parse($comentario->fecha)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                @auth
                                    @if (auth()->user()->rol === 'admin_general')
                                        <form action="{{ route('comentarios.destroy', $comentario->id_comentario) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                            <p class="text-muted small mb-0">{{ $comentario->comentario }}</p>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">No hay comentarios aún. ¡Sé el primero!</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
    <style>
        .badge-eco {
            color: #1e2a22 !important;
            background-color: #e2ece9 !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "{{ config('services.google_maps.key') }}",
        v: "weekly",
    });

    let mapaRuta, directionsRenderer, directionsService, marcadores = [];

    async function initMapaRuta() {
        const destinos = {!! $destinosJson !!};
        if (!destinos || destinos.length === 0) {
            document.getElementById('mapa-ruta').innerHTML = '<div class="alert alert-warning m-3">No hay coordenadas para mostrar el mapa.</div>';
            return;
        }

        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // Centrar en el primer destino
        mapaRuta = new Map(document.getElementById("mapa-ruta"), {
            zoom: 10,
            center: { lat: destinos[0].lat, lng: destinos[0].lng },
            mapId: "DEMO_MAP_ID",
        });

        // Configurar Directions
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: { strokeColor: '#4285F4', strokeOpacity: 1, strokeWeight: 6 }
        });
        directionsRenderer.setMap(mapaRuta);

        // Si hay más de un destino, trazar la ruta
        if (destinos.length > 1) {
            const waypoints = destinos.slice(1, -1).map(p => ({
                location: new google.maps.LatLng(p.lat, p.lng),
                stopover: true
            }));
            const request = {
                origin: new google.maps.LatLng(destinos[0].lat, destinos[0].lng),
                destination: new google.maps.LatLng(destinos[destinos.length-1].lat, destinos[destinos.length-1].lng),
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
                optimizeWaypoints: false
            };
            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.warn('No se pudo trazar la ruta:', status);
                }
                // Colocar marcadores después de la ruta (o aunque falle)
                destinos.forEach(d => colocarMarcador(d, AdvancedMarkerElement));
            });
        } else {
            // Un solo destino: solo marcador
            colocarMarcador(destinos[0], AdvancedMarkerElement);
            mapaRuta.setZoom(13);
        }
    }

    async function colocarMarcador(destino, AdvancedMarkerElement) {
        // Crear contenido HTML para la ventana de información
        const contenido = `
            <div style="font-family: 'Inter', sans-serif; padding: 6px; max-width: 260px;">
                <strong style="color: #1F6B4B; font-size: 13px;">${destino.nombre}</strong><br>
                <span style="font-size: 11px; color: #555;">Parada ${destino.orden}</span>
                <hr style="margin: 6px 0;">
                <span style="font-size: 11px; color: #888;">📍 ${destino.lat.toFixed(5)}, ${destino.lng.toFixed(5)}</span>
                <br>
                <a href="https://www.google.com/maps/search/?api=1&query=${destino.lat},${destino.lng}" 
                   target="_blank" style="font-size: 10px; color: #1F6B4B;">Ver en Google Maps →</a>
            </div>
        `;

        // Crear pin personalizado con número
        const pin = document.createElement('div');
        pin.style.cssText = 'position:relative; display:flex; flex-direction:column; align-items:center;';
        const circulo = document.createElement('div');
        circulo.style.cssText = `
            background:#1F6B4B; color:white; border-radius:50%;
            width:34px; height:34px; display:flex; align-items:center;
            justify-content:center; font-weight:bold; font-size:13px;
            border:2px solid white; box-shadow:0 2px 6px rgba(0,0,0,.4);
        `;
        circulo.textContent = String(destino.orden);
        const flecha = document.createElement('div');
        flecha.style.cssText = `
            width:0; height:0;
            border-left:7px solid transparent; border-right:7px solid transparent;
            border-top:10px solid #1F6B4B; margin-top:-2px;
        `;
        pin.appendChild(circulo);
        pin.appendChild(flecha);

        const marker = new AdvancedMarkerElement({
            position: { lat: destino.lat, lng: destino.lng },
            map: mapaRuta,
            title: destino.nombre,
            content: pin,
        });

        // Ventana de información al hacer clic
        const infoWindow = new google.maps.InfoWindow({ content: contenido });
        marker.addListener('click', () => {
            infoWindow.open({ anchor: marker, map: mapaRuta });
        });
        marcadores.push(marker);
    }

    // Inicializar al abrir la pestaña
    document.querySelectorAll('[data-bs-target="#tab-mapa"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function() {
            if (!mapaRuta) initMapaRuta();
        });
    });
    if (document.getElementById('tab-mapa')?.classList.contains('active')) {
        initMapaRuta();
    }
</script>
@endpush