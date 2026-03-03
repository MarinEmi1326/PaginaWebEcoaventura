@extends('layouts.app')

@section('content')

<section class="destinos-hero py-5">
  <div class="container py-4">

    {{-- Título (serif + centrado) --}}
    <div class="text-center mx-auto" style="max-width: 980px;">
      <h1 class="destinos-title mb-2">Descubre Destinos Increíbles</h1>
      <p class="destinos-subtitle mb-0">
        Explora nuestra colección de destinos turísticos, ecoturísticos y balnearios. Cada lugar es una
        aventura esperando por ti.
      </p>
    </div>

    {{-- Buscador (cápsula centrada, sin botón) --}}
    <form method="GET" class="mt-4 d-flex justify-content-center">
      <div class="destinos-search">
        <i class="bi bi-search"></i>
        <input
          type="text"
          name="q"
          value="{{ $q ?? '' }}"
          placeholder="Buscar destinos..."
          aria-label="Buscar destinos"
        >
      </div>
    </form>

    {{-- Tabs cápsula + botón filtros --}}
    <div class="mt-5 d-flex align-items-center justify-content-between flex-wrap gap-3">

      <div class="destinos-tabs">
        <a href="{{ route('destinos.index', ['q' => $q ?? null]) }}"
           class="destinos-tab {{ empty($tipo) ? 'active' : '' }}">
          Todos
        </a>

        <a href="{{ route('destinos.tipo', ['tipo' => 'turisticos', 'q' => $q ?? null]) }}"
           class="destinos-tab {{ ($tipo ?? '') === 'turisticos' ? 'active' : '' }}">
          Turísticos
        </a>

        <a href="{{ route('destinos.tipo', ['tipo' => 'ecoturisticos', 'q' => $q ?? null]) }}"
           class="destinos-tab {{ ($tipo ?? '') === 'ecoturisticos' ? 'active' : '' }}">
          Ecoturísticos
        </a>

        <a href="{{ route('destinos.tipo', ['tipo' => 'balnearios', 'q' => $q ?? null]) }}"
           class="destinos-tab {{ ($tipo ?? '') === 'balnearios' ? 'active' : '' }}">
          Balnearios
        </a>
      </div>

      <button type="button" class="destinos-filter-btn">
        <i class="bi bi-funnel"></i>
        Filtros
      </button>
    </div>

    {{-- Cards --}}
    <div class="row mt-4 g-4">
      @forelse($cards as $c)
        <div class="col-12 col-md-6 col-lg-4">
          <article class="card h-100 shadow-sm border-0 overflow-hidden" style="border-radius: 1.25rem;">

            <div class="position-relative">
              <img src="{{ $c['img'] }}" alt="{{ $c['titulo'] }}"
                   class="w-100"
                   style="height: 220px; object-fit: cover;">

              <span class="badge rounded-pill text-bg-light position-absolute top-0 start-0 m-3 px-3 py-2 shadow-sm">
                <span class="text-success fw-bold">{{ $c['tipo'] }}</span>
              </span>

              {{-- Favorito (gris al inicio, cambia a rojo al click) --}}
              <button type="button"
                      class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow-sm favorite-btn"
                      style="width: 44px; height: 44px;"
                      aria-label="Favorito">
                <i class="bi bi-heart favorite-icon"></i>
              </button>

              <span class="badge bg-success position-absolute bottom-0 end-0 m-3 px-3 py-2 shadow">
                <span class="fw-bold">Desde ${{ $c['precio'] }}</span>
              </span>
            </div>

            <div class="card-body p-4">
              <div class="text-muted small fw-semibold">{{ $c['tipo'] }}</div>
              <h3 class="h5 fw-bold text-dark mt-1 mb-2">{{ $c['titulo'] }}</h3>
              <p class="text-muted mb-3">{{ $c['descripcion'] }}</p>

              <div class="d-flex flex-wrap gap-2">
                @foreach($c['chips'] as $chip)
                  <span class="badge text-bg-light border fw-semibold">{{ $chip }}</span>
                @endforeach
              </div>

              <hr class="my-4">

              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                  <span class="d-inline-flex align-items-center gap-1 fw-semibold text-dark">
                    <i class="bi bi-star-fill text-warning"></i>
                    {{ $c['rating'] }}
                  </span>
                  <span class="text-muted">({{ $c['reviews'] }} reseñas)</span>
                </div>

                <a href="{{ $c['url'] }}" class="fw-bold text-success text-decoration-none">
                  Ver detalles <i class="bi bi-arrow-right"></i>
                </a>
              </div>

              @if(!empty($c['ubicacion']))
                <div class="text-muted small mt-3">
                  <i class="bi bi-geo-alt"></i> {{ $c['ubicacion'] }}
                </div>
              @endif
            </div>

          </article>
        </div>
      @empty
        <div class="col-12">
          <div class="alert alert-warning mb-0">
            No se encontraron destinos con ese filtro.
          </div>
        </div>
      @endforelse
    </div>

  </div>
</section>

{{-- JS favorito --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".favorite-btn").forEach(btn => {
    btn.addEventListener("click", function () {
      const icon = this.querySelector(".favorite-icon");

      icon.classList.toggle("active");

      if (icon.classList.contains("active")) {
        icon.classList.remove("bi-heart");
        icon.classList.add("bi-heart-fill");
      } else {
        icon.classList.remove("bi-heart-fill");
        icon.classList.add("bi-heart");
      }
    });
  });
});
</script>

@endsection