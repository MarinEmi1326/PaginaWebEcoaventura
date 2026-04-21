@extends('layouts.app')

@section('content')

{{-- ══════════════════════════════════════
     ENCABEZADO
══════════════════════════════════════ --}}
<div class="container mt-4 mb-2">
    <a href="{{ route('ruta') }}" class="text-decoration-none text-success small">
        <i class="bi bi-arrow-left me-1"></i> Volver a rutas
    </a>
</div>

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

{{-- ══════════════════════════════════════
     MAPA + INFO
══════════════════════════════════════ --}}
<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div id="mapa-detalle" class="rounded-4 shadow-sm" style="height: 420px; width: 100%;"></div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Sobre esta ruta</h5>
                    <p class="text-muted small">{{ $ruta->descripcion }}</p>

                    {{-- Recomendaciones (texto oscuro) --}}
                    @if (isset($recomendaciones) && count($recomendaciones) > 0)
                        <div class="p-3 rounded-3 mt-3" style="background:#f0f7f4; border-left: 3px solid #1F6B4B;">
                            <div class="small fw-semibold mb-1" style="color:#1F6B4B;">
                                <i class="bi bi-lightbulb me-1"></i>Recomendaciones
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach ($recomendaciones as $recomendacion)
                                    <span class="badge badge-eco" style="color: #1e2a22; background-color: #e2ece9;">{{ $recomendacion }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Resumen --}}
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-list-check me-1"></i>Resumen</h6>
                        <table class="table table-sm table-borderless small text-muted mb-0">
                            <tbody>
                                @if ($ruta->duracion_estimada)
                                    <tr>
                                        <td class="ps-0 text-dark fw-semibold">Duración</td>
                                        <td class="text-end">{{ $ruta->duracion_estimada }}</td>
                                    </tr>
                                @endif
                                @if ($ruta->distancia_km)
                                    <tr>
                                        <td class="ps-0 text-dark fw-semibold">Distancia</td>
                                        <td class="text-end">{{ $ruta->distancia_km }} km</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="ps-0 text-dark fw-semibold">Dificultad</td>
                                    <td class="text-end">{{ ucfirst($ruta->dificultad) }}</td>
                                </tr>
                                @if ($ruta->fecha_inicio_operacion && $ruta->fecha_fin_operacion)
                                    <tr>
                                        <td class="ps-0 text-dark fw-semibold">Mejor época</td>
                                        <td class="text-end">
                                            {{ \Carbon\Carbon::parse($ruta->fecha_inicio_operacion)->format('M') }}
                                            –
                                            {{ \Carbon\Carbon::parse($ruta->fecha_fin_operacion)->format('M') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="ps-0 text-dark fw-semibold">Paradas</td>
                                    <td class="text-end">{{ $destinos->count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     PUNTOS DE INTERÉS
══════════════════════════════════════ --}}
<div class="container mb-5">
    <h4 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2 text-success"></i>Puntos de Interés</h4>
    @foreach ($destinos as $destino)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="background: #1F6B4B; color: white; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                            {{ $destino->orden }}
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-bold mb-1">{{ $destino->nombre }}</h6>
                            @if ($destino->actividades->count() > 0)
                                <span class="small text-muted">{{ $destino->actividades->count() }} actividad(es)</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">{{ Str::limit($destino->descripcion, 120) }}</p>
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
        </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════
     COMENTARIOS
══════════════════════════════════════ --}}
<div class="container mb-5">
    <h4 class="fw-bold mb-4"><i class="bi bi-chat-left-text me-2 text-success"></i>Comentarios y Calificaciones</h4>
    @if (session('success')) <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div> @endif

    @auth
        @if (auth()->user()->rol === 'turista')
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('comentarios.ruta.store', $ruta->id_ruta) }}" method="POST">
                        @csrf
                        <label class="form-label fw-semibold small">Escribe tu comentario</label>
                        <textarea name="comentario" rows="3" class="form-control rounded-3 mb-3" placeholder="Comparte tu experiencia en esta ruta..." required maxlength="1000"></textarea>
                        <button type="submit" class="btn btn-success rounded-3 px-4"><i class="bi bi-send me-1"></i> Publicar comentario</button>
                    </form>
                </div>
            </div>
        @endif
    @else
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <p class="text-muted small mb-3">Inicia sesión como turista para comentar y calificar.</p>
                <a href="{{ route('login') }}" class="btn btn-outline-success rounded-3 px-4"><i class="bi bi-person me-1"></i> Iniciar Sesión</a>
            </div>
        </div>
    @endauth

    @forelse ($comentarios as $comentario)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="background: #e2ece9; color: #1F6B4B; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                            {{ strtoupper(substr($comentario->nombre, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ $comentario->nombre }} {{ $comentario->apellidos }}</div>
                            <div class="text-muted" style="font-size: .75rem;">{{ \Carbon\Carbon::parse($comentario->fecha)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    @auth
                        @if (auth()->user()->rol === 'admin_general')
                            <form action="{{ route('comentarios.destroy', $comentario->id_comentario) }}" method="POST" onsubmit="return confirm('¿Eliminar este comentario?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
                            </form>
                        @endif
                    @endauth
                </div>
                <p class="text-muted small mb-0">{{ $comentario->comentario }}</p>
            </div>
        </div>
    @empty
        <div class="text-center py-4 text-muted small"><i class="bi bi-chat-left me-1"></i> Aún no hay comentarios. ¡Sé el primero en comentar!</div>
    @endforelse
</div>

@endsection

@push('scripts')
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

    let mapaRuta, directionsService, directionsRenderer, infoWindow;

    async function initMapaRuta() {
        const destinos = {!! $destinosJson !!};
        const contenedor = document.getElementById('mapa-detalle');
        if (!contenedor) return;

        if (!destinos || destinos.length === 0) {
            contenedor.innerHTML = '<div class="alert alert-warning">No hay coordenadas disponibles para esta ruta.</div>';
            return;
        }

        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        const primerDestino = destinos[0];
        mapaRuta = new Map(contenedor, {
            zoom: 12,
            center: { lat: primerDestino.lat, lng: primerDestino.lng },
            mapId: "DEMO_MAP_ID",
        });

        infoWindow = new google.maps.InfoWindow();

        // Colocar marcadores numerados
        destinos.forEach(destino => {
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

            marker.addListener("click", () => {
                infoWindow.setContent(`
                    <div style="padding:6px;">
                        <strong style="color:#1F6B4B;">${destino.nombre}</strong><br>
                        <span style="font-size:11px;">Parada ${destino.orden}</span><br>
                        <span style="font-size:10px; color:#888;">📍 ${destino.lat.toFixed(5)}, ${destino.lng.toFixed(5)}</span><br>
                        <a href="https://www.google.com/maps/search/?api=1&query=${destino.lat},${destino.lng}" target="_blank" style="font-size:10px;">Ver en Google Maps →</a>
                    </div>
                `);
                infoWindow.open({ anchor: marker, map: mapaRuta });
            });
        });

        // Trazar ruta si hay más de un destino
        if (destinos.length > 1) {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: { strokeColor: '#4285F4', strokeOpacity: 1, strokeWeight: 6 }
            });
            directionsRenderer.setMap(mapaRuta);

            const origen = destinos[0];
            const destinoFinal = destinos[destinos.length - 1];
            const waypoints = destinos.slice(1, -1).map(p => ({
                location: new google.maps.LatLng(p.lat, p.lng),
                stopover: true,
            }));

            directionsService.route({
                origin: new google.maps.LatLng(origen.lat, origen.lng),
                destination: new google.maps.LatLng(destinoFinal.lat, destinoFinal.lng),
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

    // Inicializar cuando el DOM esté listo (el mapa ya está visible)
    document.addEventListener('DOMContentLoaded', function() {
        initMapaRuta();
    });
</script>
@endpush