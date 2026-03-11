@extends('layouts.app')

@push('styles')
<style>
    #map { height: 500px; width: 100%; }
</style>

@section('content')
<div class="row justify-content-center" style="min-height: 80vh">
    <div class="col-5">
        <table class="table table-success table-striped table-hover">
            <tr class="table-secondary"><th>RUTAS</th></tr>
            @foreach($rutas as $ruta)
            <tr>
                <td>
                    <a href="#/" onclick="centrar({{$ruta->punto_inicio_lat}}, {{$ruta->punto_inicio_lng}})">
                        {{ $ruta->nombre }}
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="col-5">
        <div id="map"></div>
    </div>
</div>

<script>
  (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    key: "{{ config('services.google_maps.key') }}",
    v: "weekly",
  });

  let map, infowindow;
  async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: { lat: 16.862376997318453, lng: -92.05375658886717 },
        mapId: "DEMO_MAP_ID",
    });

    var markers = [];
    @foreach($rutas as $ruta)
    @if($ruta->punto_inicio_lat && $ruta->punto_inicio_lng)
    markers[markers.length] = new AdvancedMarkerElement({
        map: map,
        position: { lat: {{$ruta->punto_inicio_lat}}, lng: {{$ruta->punto_inicio_lng}} },
        title: '{{$ruta->nombre}}',
    });

    markers[markers.length-1].addListener("gmp-click", () => {
        if (infowindow) infowindow.close();
        infowindow = new google.maps.InfoWindow({
            content: "<h5>{{$ruta->nombre}}</h5>" +
                     "<p><strong>Dificultad:</strong> {{$ruta->dificultad}}</p>" +
                     "<p><strong>Distancia:</strong> {{$ruta->distancia_km ?? 'N/D'}} km</p>" +
                     "<p><strong>Duración:</strong> {{$ruta->duracion_estimada ?? 'N/D'}}</p>" +
                     "<p>{{$ruta->descripcion}}</p>",
        });
        infowindow.open({ anchor: event.target, map });
    });
    @endif
    @endforeach
  }
  initMap();

  function centrar(latitud, longitud) {
    map.setCenter({ lat: latitud, lng: longitud });
    map.setZoom(12);
  }
</script>
@endsection