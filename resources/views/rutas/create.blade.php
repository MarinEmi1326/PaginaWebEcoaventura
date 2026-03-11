@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-5">
        <form id="formRuta" action="{{route('rutas.store')}}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="dificultad" class="form-label">Dificultad</label>
                <select class="form-select" name="dificultad" id="dificultad" required>
                    <option value="">-- Selecciona --</option>
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="distancia_km" class="form-label">Distancia (km)</label>
                <input type="number" step="0.01" class="form-control" id="distancia_km" name="distancia_km">
            </div>

            <div class="mb-3">
                <label for="duracion_estimada" class="form-label">Duración estimada</label>
                <input type="text" class="form-control" id="duracion_estimada" name="duracion_estimada" placeholder="Ej: 2 horas">
            </div>

            <div class="mb-3">
                <label for="recomendaciones" class="form-label">Recomendaciones</label>
                <textarea class="form-control" name="recomendaciones" id="recomendaciones" cols="30" rows="3"></textarea>
            </div>

            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="punto_inicio_lat" class="form-label">Latitud inicio</label>
                        <input type="text" class="form-control" id="punto_inicio_lat" name="punto_inicio_lat" readonly required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="punto_inicio_lng" class="form-label">Longitud inicio</label>
                        <input type="text" class="form-control" id="punto_inicio_lng" name="punto_inicio_lng" readonly required>
                    </div>
                </div>
            </div>

            <p class="text-muted small">📍 Haz clic en el mapa para marcar el punto de inicio.</p>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        </form>
    </div>

    <div class="col-5">
        <div id="map" style="height:500px; width:100%;"></div>
    </div>
</div>

<script>
  (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    key: "{{ config('services.google_maps.key') }}",
    v: "weekly",
  });

  async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: { lat: 16.862376997318453, lng: -92.05375658886717 },
        mapId: "DEMO_MAP_ID",
    });

    var marker = new AdvancedMarkerElement({
        map: map,
        position: map.center,
        title: 'Punto de inicio',
    });

    map.addListener("click", (e) => {
        marker.position = e.latLng;
        document.getElementById("punto_inicio_lat").value = e.latLng.lat();
        document.getElementById("punto_inicio_lng").value = e.latLng.lng();
    });
  }
  initMap();

  $('#formRuta').on('submit', function(e) {
    $('[required]').each(function(i, el) {
        if ($(el).val() == '' || $(el).val() == undefined) {
            alert('Por favor llena todos los campos requeridos.');
            e.preventDefault();
            return false;
        }
    });
  });
</script>
@endsection