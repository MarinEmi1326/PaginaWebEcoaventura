@extends('layouts.admin')

@section('title', 'Editar Destino')

@section('content')
    <div class="mb-4">
        <a href="{{ route('misdestinos.index') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">← Regresar a mis destinos</a>
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
                <div class="fw-semibold"><i class="bi bi-geo-fill me-2"></i>Información del Destino</div>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre del destino <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre', $destino->nombre) }}" class="form-control rounded-3 py-2" required maxlength="120">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción completa <span class="text-danger">*</span></label>
                        <textarea name="descripcion" rows="4" class="form-control rounded-3 py-2" required>{{ old('descripcion', $destino->descripcion) }}</textarea>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Teléfono <span class="text-muted small fw-normal">(opcional)</span></label>
                        <input type="text" name="telefono" value="{{ old('telefono', $destino->telefono) }}" class="form-control rounded-3 py-2" maxlength="20">
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 2: Ubicación con mapa --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"><div class="fw-semibold"><i class="bi bi-map me-2"></i>Ubicación</div></div>
            <div class="p-4">
                <p class="text-muted small mb-3"><i class="bi bi-cursor-fill me-1"></i> Haz clic en el mapa para actualizar la ubicación.</p>
                <div id="mapa-destino" class="rounded-3 mb-3" style="height: 350px; width: 100%;"></div>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Latitud</label>
                        <input type="number" step="0.0000001" name="lat" id="input-lat" value="{{ old('lat', $destino->lat) }}" class="form-control rounded-3 py-2">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Longitud</label>
                        <input type="number" step="0.0000001" name="lng" id="input-lng" value="{{ old('lng', $destino->lng) }}" class="form-control rounded-3 py-2">
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: Categorías --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"><div class="fw-semibold"><i class="bi bi-tags me-2"></i>Categorías</div></div>
            <div class="p-4">
                @if ($categorias->count() > 0)
                    <div class="row g-2">
                        @foreach ($categorias as $cat)
                            <div class="col-12 col-md-4">
                                <label class="d-flex align-items-center gap-2 p-3 rounded-3 border" style="cursor:pointer; background:#f7f9f7;">
                                    <input type="checkbox" name="categorias[]" value="{{ $cat->id_categoria }}" class="form-check-input flex-shrink-0"
                                           {{ in_array($cat->id_categoria, old('categorias', $categoriasDelDestino)) ? 'checked' : '' }}>
                                    <span class="fw-semibold small">{{ $cat->nombre }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small text-center py-3">Aún no hay categorías registradas.</div>
                @endif
            </div>
        </div>

        {{-- BLOQUE 4: Actividades (solo selección, sin creación) --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"><div class="fw-semibold"><i class="bi bi-run me-2"></i>Actividades</div></div>
            <div class="p-4">
                @if ($actividades->count() > 0)
                    <div class="row g-2">
                        @foreach ($actividades as $act)
                            <div class="col-12 col-md-4">
                                <label class="d-flex align-items-center gap-2 p-3 rounded-3 border" style="cursor:pointer; background:#f7f9f7;">
                                    <input type="checkbox" name="actividades[]" value="{{ $act->id_actividad }}" class="form-check-input flex-shrink-0"
                                           {{ in_array($act->id_actividad, old('actividades', $actividadesDelDestino)) ? 'checked' : '' }}>
                                    <span class="fw-semibold small">{{ $act->nombre }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small text-center py-3">Aún no hay actividades registradas. El administrador general debe crearlas primero.</div>
                @endif
            </div>
        </div>

        {{-- BLOQUE 5: Recomendaciones --}}
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"><div class="fw-semibold"><i class="bi bi-chat-dots me-2"></i>Recomendaciones</div></div>
            <div class="p-4">
                @if ($recomendaciones->count() > 0)
                    <div class="row g-2">
                        @foreach ($recomendaciones as $rec)
                            <div class="col-12 col-md-4">
                                <label class="d-flex align-items-center gap-2 p-3 rounded-3 border" style="cursor:pointer; background:#f7f9f7;">
                                    <input type="checkbox" name="recomendaciones[]" value="{{ $rec->id_recomendacion }}" class="form-check-input flex-shrink-0"
                                           {{ in_array($rec->id_recomendacion, old('recomendaciones', $recomendacionesDelDestino)) ? 'checked' : '' }}>
                                    <span class="fw-semibold small">{{ $rec->descripcion }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small text-center py-3">Aún no hay recomendaciones registradas.</div>
                @endif
            </div>
        </div>

        {{-- BLOQUE 6: Imágenes existentes --}}
        @if ($imagenes->count() > 0)
        <div class="ea-card p-0 overflow-hidden mb-4">
            <div class="p-4 border-bottom"><div class="fw-semibold"><i class="bi bi-images me-2"></i>Imágenes actuales</div></div>
            <div class="p-4">
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($imagenes as $img)
                        <div class="position-relative" id="img-{{ $img->id_imagen }}">
                            <img src="{{ Storage::url($img->ruta_archivo) }}" class="rounded-3 border" style="width:120px; height:90px; object-fit:cover;">
                            <button type="button" onclick="eliminarImagen({{ $img->id_imagen }})"
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
            <div class="p-4 border-bottom"><div class="fw-semibold"><i class="bi bi-images me-2"></i>Agregar nuevas imágenes</div></div>
            <div class="p-4">
                <p class="text-muted small mb-3">Puedes subir varias imágenes (JPG/PNG, máx. 5MB c/u).</p>
                <input type="file" id="fotosInput" name="fotos[]" accept="image/png,image/jpeg" class="d-none" multiple>
                <div id="dropzone" class="rounded-3 border border-2 border-dashed p-5 text-center" style="border-color: #cdd8cd !important; background: #f7f9f7; cursor:pointer;" onclick="document.getElementById('fotosInput').click()">
                    <div class="mx-auto mb-3 rounded-3 d-inline-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e2ece9;"><i class="bi bi-cloud-arrow-up text-success fs-4"></i></div>
                    <div class="fw-semibold text-dark">Haz clic para subir o arrastra imágenes</div>
                    <div class="small text-muted mt-1">PNG, JPG hasta 5MB cada una</div>
                </div>
                <div id="previews" class="d-flex flex-wrap gap-2 mt-3"></div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-2 pt-4 border-top">
            <a href="{{ route('misdestinos.index') }}" class="btn btn-light border rounded-3 px-4 py-2">Cancelar</a>
            <button type="submit" class="btn ea-btn-green rounded-3 px-4 py-2">Guardar cambios</button>
        </div>
    </form>

    <script>
        // Eliminar imagen (AJAX)
        async function eliminarImagen(id) {
            if (!confirm('¿Eliminar esta imagen?')) return;
            const res = await fetch(`/destinos/imagen/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            if (res.ok) {
                document.getElementById('img-' + id).remove();
            } else {
                alert('Error al eliminar la imagen.');
            }
        }

        // Google Maps (igual que en create)
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
                marcadorActivo = new AdvancedMarkerElement({ map: mapaDestino, position: { lat: latInicial, lng: lngInicial } });
            }
            mapaDestino.addListener("click", async (e) => {
                const lat = e.latLng.lat(), lng = e.latLng.lng();
                document.getElementById('input-lat').value = lat.toFixed(7);
                document.getElementById('input-lng').value = lng.toFixed(7);
                if (marcadorActivo) marcadorActivo.map = null;
                marcadorActivo = new AdvancedMarkerElement({ map: mapaDestino, position: { lat, lng } });
            });
        }
        initMapaDestino();

        // Previsualización de nuevas imágenes
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
        dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = '#cdd8cd'; });
        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.style.borderColor = '#cdd8cd';
            fotosInput.files = e.dataTransfer.files;
            mostrarPreviews(e.dataTransfer.files);
        });
    </script>
@endsection