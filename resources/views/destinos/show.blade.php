@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilodetalle.css') }}?v={{ filemtime(public_path('css/estilodetalle.css')) }}">
@endpush

@section('content')

<section class="destino-detail py-5">
  <div class="container">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="{{ url('/') }}" class="text-decoration-none">Inicio</a>
        </li>

        <li class="breadcrumb-item">
          <a href="{{ route('destinos.index') }}" class="text-decoration-none">
            Centros Turísticos
          </a>
        </li>

        <li class="breadcrumb-item active" aria-current="page">
          {{ $d['titulo'] }}
        </li>
      </ol>
    </nav>

    {{-- Galería --}}
    <div class="destino-gallery">
      <div class="g-big">
        <img src="{{ $d['galeria'][0] }}" alt="{{ $d['titulo'] }}">
      </div>
      <div class="g-right">
        <div class="g-small">
          <img src="{{ $d['galeria'][1] }}" alt="{{ $d['titulo'] }} 2">
        </div>
        <div class="g-small">
          <img src="{{ $d['galeria'][2] }}" alt="{{ $d['titulo'] }} 3">
        </div>
      </div>
    </div>

    {{-- Chip + ubicación --}}
    <div class="mt-4 d-flex align-items-center gap-3 flex-wrap">
      <span class="chip">
        <i class="bi bi-bank"></i> {{ $d['categoria'] }}
      </span>
      <span class="muted">
        <i class="bi bi-geo-alt"></i> {{ $d['ubicacion'] }}
      </span>
    </div>

    {{-- Título --}}
    <h1 class="destino-title mt-2">{{ $d['titulo'] }}</h1>

    {{-- Tarjetas (4) --}}
    <div class="row g-3 mt-3">
      @foreach($d['stats'] as $s)
        <div class="col-12 col-md-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="bi {{ $s['icon'] }}"></i></div>
            <div class="stat-label">{{ $s['label'] }}</div>
            <div class="stat-value">{{ $s['value'] }}</div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Tabs --}}
    <div class="destino-tabs mt-4">
      <button class="dtab active" data-tab="descripcion">Descripción</button>
      <button class="dtab" data-tab="info">Info Práctica</button>
      <button class="dtab" data-tab="recomendaciones">Recomendaciones</button>
      <button class="dtab" data-tab="ubicacion">Ubicación</button>
    </div>

    {{-- Contenido --}}
    <div class="destino-tab-content mt-3">

      {{-- DESCRIPCIÓN --}}
      <div class="tab-pane active" id="descripcion">
        <p class="desc-text">{{ $d['tabs']['descripcion']['texto'] }}</p>

        <div class="highlight-card mt-4">
          <div class="highlight-title">
            <i class="bi bi-leaf"></i> {{ $d['tabs']['descripcion']['highlight']['titulo'] }}
          </div>
          <div class="highlight-body">
            {{ $d['tabs']['descripcion']['highlight']['texto'] }}
          </div>
        </div>
      </div>

      {{-- INFO --}}
      <div class="tab-pane" id="info">
        <div class="row g-3">
          @foreach($d['tabs']['info']['cards'] as $card)
            <div class="col-12 col-lg-6">
              <div class="info-card">
                <div class="info-label">{{ $card['label'] }}</div>

                @if(!empty($card['value']))
                  <div class="info-value">{{ $card['value'] }}</div>
                @endif

                @if(!empty($card['chips']))
                  <div class="info-chips mt-2">
                    @foreach($card['chips'] as $chip)
                      <span class="chip-mini">{{ $chip }}</span>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- RECOMENDACIONES --}}
      <div class="tab-pane" id="recomendaciones">
        <div class="reco-list">
          @foreach($d['tabs']['recomendaciones']['items'] as $item)
            <div class="reco-item">
              <i class="bi bi-exclamation-triangle"></i>
              <span>{{ $item }}</span>
            </div>
          @endforeach
        </div>
      </div>

      {{-- UBICACIÓN (✅ SOLO 1 MAPA) --}}
      <div class="tab-pane" id="ubicacion">
        <div class="highlight-card">

          <div id="map" style="height:400px; width:100%; border-radius:14px;"></div>

          <div class="mt-3">
            <strong>Acceso:</strong>
            <span class="muted">
              {{ collect($d['tabs']['info']['cards'])->firstWhere('label','Acceso')['value'] ?? 'Consulta el acceso en Info Práctica.' }}
            </span>
          </div>

        </div>
      </div>

    </div> {{-- /destino-tab-content --}}

    {{-- Comentarios --}}
    <div class="mt-5">
      <h3 class="section-title"><i class="bi bi-chat-left-text"></i> Comentarios y Calificaciones</h3>

      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="stars">
          @for($i=1; $i<=5; $i++)
            <i class="bi {{ $i <= round($d['rating']) ? 'bi-star-fill' : 'bi-star' }}"></i>
          @endfor
        </div>
        <strong>{{ $d['rating'] }}</strong>
        <span class="muted">({{ $d['reviews_count'] }} reseñas)</span>
      </div>

      {{-- ✅ NO sesión --}}
      @guest
        <div class="login-box">
          <div class="muted">Inicia sesión como Turista para comentar y calificar</div>
          <a href="{{ route('login') }}" class="login-btn">
            <i class="bi bi-person"></i> Iniciar Sesión
          </a>
        </div>
      @endguest

      {{-- ✅ SI sesión (solo UI, aún no guarda en BD) --}}
      @auth
        <div class="review-box">
          <div class="review-title">Deja tu reseña</div>

          <form method="POST" action="#">
            @csrf

            <div class="review-rating">
              <span class="muted">Tu calificación:</span>
              <div class="rate-stars" data-value="0">
                @for($i=1; $i<=5; $i++)
                  <i class="bi bi-star" data-star="{{ $i }}"></i>
                @endfor
              </div>
              <input type="hidden" name="stars" id="starsInput" value="0">
            </div>

            <textarea name="comentario" class="review-textarea" rows="4" placeholder="Comparte tu experiencia..." required></textarea>

            <button type="submit" class="review-btn">
              <i class="bi bi-send"></i> Publicar reseña
            </button>
          </form>
        </div>
      @endauth

      {{-- Lista de comentarios --}}
      <div class="mt-3 d-flex flex-column gap-3">
        @foreach($d['comentarios'] as $c)
          <div class="comment-card">
            <div class="comment-left">
              <div class="avatar">{{ mb_substr($c['nombre'],0,1) }}</div>
              <div>
                <div class="comment-name">{{ $c['nombre'] }}</div>
                <div class="muted">{{ $c['fecha'] }}</div>
              </div>
            </div>

            <div class="comment-right">
              <div class="stars">
                @for($i=1; $i<=5; $i++)
                  <i class="bi {{ $i <= $c['stars'] ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
              </div>
            </div>

            <div class="comment-text">{{ $c['texto'] }}</div>
          </div>
        @endforeach
      </div>

      {{-- Servicios relacionados --}}
      <div class="mt-5">
        <h3 class="section-title">Servicios relacionados</h3>
        <div class="row g-3">
          @foreach($d['servicios'] as $s)
            <div class="col-12 col-md-6">
              <div class="service-card">
                <div class="service-icon"><i class="bi {{ $s['icon'] }}"></i></div>
                <div>
                  <div class="service-title">{{ $s['titulo'] }}</div>
                  <div class="muted">{{ $s['desc'] }}</div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div> {{-- /Comentarios --}}

  </div>
</section>

{{-- Tabs + estrellas + mapa --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

  // Tabs
  document.querySelectorAll(".dtab").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".dtab").forEach(b => b.classList.remove("active"));
      document.querySelectorAll(".tab-pane").forEach(p => p.classList.remove("active"));
      btn.classList.add("active");
      document.getElementById(btn.dataset.tab).classList.add("active");
    });
  });

  // Estrellas reseña (solo UI)
  const rate = document.querySelector(".rate-stars");
  const input = document.getElementById("starsInput");

  if(rate && input){
    const stars = rate.querySelectorAll("i");

    const paint = (value) => {
      stars.forEach(s => {
        const n = Number(s.dataset.star);
        s.classList.toggle("active", n <= value);
        s.classList.toggle("bi-star-fill", n <= value);
        s.classList.toggle("bi-star", n > value);
      });
    };

    stars.forEach(s => {
      s.addEventListener("click", () => {
        const value = Number(s.dataset.star);
        input.value = value;
        paint(value);
      });
    });

    paint(Number(input.value || 0));
  }

});

// ✅ Google Map (SOLO UNO)
function initMap() {
  const ubicacion = { lat: 16.90780, lng: -92.09502 };

  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 14,
    center: ubicacion,
  });

  new google.maps.Marker({
    position: ubicacion,
    map: map,
  });
}
</script>

<script async
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap">
</script>

@endsection