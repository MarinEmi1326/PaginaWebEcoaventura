@extends('layouts.app')

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="py-5 bg-light text-center">
        <div class="container">
            <span class="text-success fw-bold text-uppercase small">Explora Ocosingo</span>
            <h1 class="display-5 fw-bold mt-2">Rutas Turísticas</h1>
            <p class="lead text-muted">
                Descubre los recorridos más fascinantes por la región de Ocosingo.
                Planifica tu aventura con rutas detalladas y guías paso a paso.
            </p>

            {{-- Buscador con ícono --}}
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 50rem 0 0 50rem;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0"
                            placeholder="Buscar rutas, lugares, actividades..."
                            style="border-radius: 0 50rem 50rem 0;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FILTROS (sticky) ===================== --}}
    <section class="py-3 border-bottom bg-white sticky-top" style="top: 75px; z-index: 100;">
        <div class="container">
            <div class="d-flex flex-wrap gap-2 align-items-center">

                <i class="bi bi-funnel text-muted me-1"></i>

                {{-- Filtros por tipo --}}
                <button class="btn btn-success btn-sm rounded-pill">Todas</button>
                <button class="btn btn-outline-success btn-sm rounded-pill">Arqueológicas</button>
                <button class="btn btn-outline-success btn-sm rounded-pill">Naturaleza</button>
                <button class="btn btn-outline-success btn-sm rounded-pill">Comunitarias</button>
                <button class="btn btn-outline-success btn-sm rounded-pill">Culturales</button>

                <div class="vr mx-2 d-none d-md-block"></div>

                {{-- Filtros por dificultad --}}
                <button class="btn btn-secondary btn-sm rounded-pill">Toda dificultad</button>
                <button class="btn btn-outline-secondary btn-sm rounded-pill">Fácil</button>
                <button class="btn btn-outline-secondary btn-sm rounded-pill">Moderada</button>
                <button class="btn btn-outline-secondary btn-sm rounded-pill">Alta</button>

            </div>
        </div>
    </section>

    {{-- ===================== LISTA DE RUTAS ===================== --}}
    <section class="py-5">
        <div class="container">

            <p class="text-muted mb-4">5 rutas encontradas</p>

            <div class="row g-4">

                {{-- ---- CARD 1: Arqueológica Maya ---- --}}
                <div class="col-md-6 col-lg-4">
                    <a href="/rutas/ruta-arqueologica-maya" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                            <div class="bg-success bg-opacity-10 text-center py-5 rounded-top-4 position-relative">
                                <i class="bi bi-map display-4 text-success opacity-25"></i>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-dark shadow-sm">Arqueológica</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-moderada">Moderada</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">Ruta Arqueológica Maya</h5>
                                <p class="card-text text-muted small">
                                    Recorre las grandes ciudades mayas: Toniná, Bonampak y Yaxchilán.
                                    Ideal para amantes de la historia.
                                </p>

                                <div class="d-flex gap-3 small text-muted mb-3">
                                    <span><i class="bi bi-clock me-1"></i>2-3 días</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>185 km</span>
                                    <span><i class="bi bi-star-fill text-warning me-1"></i>4.8</span>
                                </div>

                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    <span class="badge badge-eco">Arqueología</span>
                                    <span class="badge badge-eco">Historia</span>
                                    <span class="badge badge-eco">UNESCO</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="small text-muted">3 paradas</span>
                                    <span class="text-success fw-semibold small">Ver ruta <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>

                {{-- ---- CARD 2: Cascadas ---- --}}
                <div class="col-md-6 col-lg-4">
                    <a href="/rutas/ruta-cascadas" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                            <div class="bg-success bg-opacity-10 text-center py-5 rounded-top-4 position-relative">
                                <i class="bi bi-map display-4 text-success opacity-25"></i>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-dark shadow-sm">Naturaleza</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-facil">Fácil</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">Ruta de Cascadas</h5>
                                <p class="card-text text-muted small">
                                    Las cascadas más espectaculares de Chiapas: aguas turquesas,
                                    pozas naturales y selva tropical.
                                </p>

                                <div class="d-flex gap-3 small text-muted mb-3">
                                    <span><i class="bi bi-clock me-1"></i>1-2 días</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>95 km</span>
                                    <span><i class="bi bi-star-fill text-warning me-1"></i>4.9</span>
                                </div>

                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    <span class="badge badge-eco">Cascadas</span>
                                    <span class="badge badge-eco">Naturaleza</span>
                                    <span class="badge badge-eco">Natación</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="small text-muted">3 paradas</span>
                                    <span class="text-success fw-semibold small">Ver ruta <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>

                {{-- ---- CARD 3: Comunitaria y Selva ---- --}}
                <div class="col-md-6 col-lg-4">
                    <a href="/rutas/ruta-comunitaria-selva" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                            <div class="bg-success bg-opacity-10 text-center py-5 rounded-top-4 position-relative">
                                <i class="bi bi-map display-4 text-success opacity-25"></i>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-dark shadow-sm">Comunitaria</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-alta">Alta</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">Ruta Comunitaria y de Selva</h5>
                                <p class="card-text text-muted small">
                                    Inmersión en la Selva Lacandona: turismo comunitario,
                                    campamentos y laguna Miramar.
                                </p>

                                <div class="d-flex gap-3 small text-muted mb-3">
                                    <span><i class="bi bi-clock me-1"></i>3-5 días</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>240 km</span>
                                    <span><i class="bi bi-star-fill text-warning me-1"></i>4.7</span>
                                </div>

                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    <span class="badge badge-eco">Selva</span>
                                    <span class="badge badge-eco">Comunitario</span>
                                    <span class="badge badge-eco">Aventura</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="small text-muted">4 paradas</span>
                                    <span class="text-success fw-semibold small">Ver ruta <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>

                {{-- ---- CARD 4: Cultural Ocosingo ---- --}}
                <div class="col-md-6 col-lg-4">
                    <a href="/rutas/ruta-cultural-ocosingo" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                            <div class="bg-success bg-opacity-10 text-center py-5 rounded-top-4 position-relative">
                                <i class="bi bi-map display-4 text-success opacity-25"></i>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-dark shadow-sm">Cultural</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-facil">Fácil</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">Ruta Cultural de Ocosingo</h5>
                                <p class="card-text text-muted small">
                                    Conoce la cabecera municipal: mercado, queso bola,
                                    iglesias coloniales y artesanías tzeltales.
                                </p>

                                <div class="d-flex gap-3 small text-muted mb-3">
                                    <span><i class="bi bi-clock me-1"></i>1 día</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>8 km</span>
                                    <span><i class="bi bi-star-fill text-warning me-1"></i>4.6</span>
                                </div>

                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    <span class="badge badge-eco">Cultura</span>
                                    <span class="badge badge-eco">Gastronomía</span>
                                    <span class="badge badge-eco">Artesanías</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="small text-muted">3 paradas</span>
                                    <span class="text-success fw-semibold small">Ver ruta <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>

                {{-- ---- CARD 5: Observación de Aves ---- --}}
                <div class="col-md-6 col-lg-4">
                    <a href="/rutas/ruta-observacion-aves" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                            <div class="bg-success bg-opacity-10 text-center py-5 rounded-top-4 position-relative">
                                <i class="bi bi-map display-4 text-success opacity-25"></i>
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-dark shadow-sm">Naturaleza</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge badge-moderada">Moderada</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">Ruta de Observación de Aves</h5>
                                <p class="card-text text-muted small">
                                    Avistamiento de aves endémicas en la Selva Lacandona:
                                    tucanes, guacamayas y quetzales.
                                </p>

                                <div class="d-flex gap-3 small text-muted mb-3">
                                    <span><i class="bi bi-clock me-1"></i>2 días</span>
                                    <span><i class="bi bi-geo-alt me-1"></i>60 km</span>
                                    <span><i class="bi bi-star-fill text-warning me-1"></i>4.5</span>
                                </div>

                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    <span class="badge badge-eco">Aves</span>
                                    <span class="badge badge-eco">Biodiversidad</span>
                                    <span class="badge badge-eco">Fotografía</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="small text-muted">2 paradas</span>
                                    <span class="text-success fw-semibold small">Ver ruta <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== TEMPORADAS ===================== --}}
    <section class="py-5 bg-light">
        <div class="container text-center">

            <h2 class="fw-bold mb-2">¿Cuándo visitar Ocosingo?</h2>
            <p class="text-muted mb-5">Cada temporada ofrece una experiencia diferente.</p>

            <div class="row g-4 justify-content-center">

                {{-- Temporada seca --}}
                <div class="col-md-5">
                    <div class="card border-success shadow-sm rounded-4 h-100">
                        <div class="card-body text-start p-4">

                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="bi bi-sun fs-3 text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Temporada Seca</h5>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <small class="text-muted">Noviembre – Abril</small>
                                        <span class="badge badge-eco" style="font-size: 0.7rem;">Recomendada</span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted small mb-3">
                                Ideal para la mayoría de actividades. Cielos despejados,
                                caminos accesibles, cascadas con colores turquesa intenso.
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-eco">Senderismo</span>
                                <span class="badge badge-eco">Cascadas</span>
                                <span class="badge badge-eco">Arqueología</span>
                                <span class="badge badge-eco">Laguna Miramar</span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Temporada lluvias --}}
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body text-start p-4">

                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="bi bi-cloud-rain fs-3 text-secondary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Temporada de Lluvias</h5>
                                    <small class="text-muted">Mayo – Octubre</small>
                                </div>
                            </div>

                            <p class="text-muted small mb-3">
                                La selva en su máximo esplendor verde. Menos visitantes,
                                precios accesibles. Algunas rutas más difíciles.
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-eco">Observación de aves</span>
                                <span class="badge badge-eco">Cultura</span>
                                <span class="badge badge-eco">Zonas arqueológicas</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection