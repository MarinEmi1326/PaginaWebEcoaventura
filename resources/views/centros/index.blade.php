@extends('layouts.app')

@section('content')


<section class="pt-2 pb-5 text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mt-2">Centros Turísticos de Ocosingo</h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
            Catálogo informativo de los principales sitios naturales, arqueológicos
            y comunitarios de la región. Conoce cada lugar antes de visitarlo.
        </p>

        {{-- Buscador --}}
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0"
                        style="border-radius: 50rem 0 0 50rem;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0"
                        placeholder="Buscar centros turísticos..."
                        style="border-radius: 0 50rem 50rem 0;">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== TABS + CARDS ===================== --}}
<section class="pt-3 pb-5">
    <div class="container">
        <div class="text-center mb-5">
            <ul class="nav nav-pills justify-content-center p-2 rounded-pill filtro-tabs d-inline-flex" id="destinosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4" id="tab-turistico"
                        data-bs-toggle="pill" data-bs-target="#turistico" type="button">
                        <i class="bi bi-building-fill me-1"></i> Turístico
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="tab-ecoturistico"
                        data-bs-toggle="pill" data-bs-target="#ecoturistico" type="button">
                        <i class="bi bi-tree-fill me-1"></i> Ecoturístico
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="tab-balnearios"
                        data-bs-toggle="pill" data-bs-target="#balnearios" type="button">
                        <i class="bi bi-water me-1"></i> Balnearios
                    </button>
                </li>
            </ul>
        </div>

        {{-- CONTENIDO TABS --}}
        <div class="tab-content" id="destinosTabContent">
            <div class="tab-pane fade show active" id="turistico" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 1) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/turisticos/tonina-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Zona Arqueológica de Toniná">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-building me-1 text-success"></i> Turístico
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Ocosingo, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Zona Arqueológica de Toniná</h5>
                                    <p class="text-muted small">Antigua ciudad maya con una de las pirámides más altas de Mesoamérica.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Pirámide 7 plataformas</span>
                                        <span class="badge badge-eco">Museo de sitio</span>
                                        <span class="badge badge-eco">Escultura maya</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>3-4 h</span>
                                            <span><i class="bi bi-people me-1"></i>Moderada</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 2) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/turisticos/ayutla-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Ayutla">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-building me-1 text-success"></i> Turístico
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Ocosingo, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Ayutla</h5>
                                    <p class="text-muted small">Famoso por sus murales policromos mejor conservados del mundo maya.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Murales policromos</span>
                                        <span class="badge badge-eco">Templo de las Pinturas</span>
                                        <span class="badge badge-eco">Estelas</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>3-4 h</span>
                                            <span><i class="bi bi-people me-1"></i>Moderada</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 3) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/turisticos/mirador-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Yaxchilán">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-building me-1 text-success"></i> Turístico
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Ocosingo, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Mirador</h5>
                                    <p class="text-muted small">Ciudad maya junto al río Usumacinta, accesible por lancha.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Río Usumacinta</span>
                                        <span class="badge badge-eco">Selva y fauna</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>4-6 h</span>
                                            <span><i class="bi bi-people me-1"></i>Alta</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ECOTURÍSTICO --}}
            <div class="tab-pane fade" id="ecoturistico" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 4) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/ecoturisticos/miramar-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Laguna Miramar">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-tree me-1 text-success"></i> Ecoturístico
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Ocosingo, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Laguna Miramar</h5>
                                    <p class="text-muted small">Laguna cristalina en el corazón de la Selva Lacandona.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Aguas cristalinas</span>
                                        <span class="badge badge-eco">Kayak y senderos</span>
                                        <span class="badge badge-eco">Turismo comunitario</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>2-3 días</span>
                                            <span><i class="bi bi-people me-1"></i>Alta</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Nahá -->
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 6) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/ecoturisticos/naha-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Comunidad Nahá">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-tree me-1 text-success"></i> Ecoturístico
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Ocosingo, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Comunidad Lacandona Nahá</h5>
                                    <p class="text-muted small">Turismo comunitario, laguna y cultura lacandona viva.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Biodiversidad</span>
                                        <span class="badge badge-eco">Cultura lacandona</span>
                                        <span class="badge badge-eco">Laguna y aves</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>1-3 días</span>
                                            <span><i class="bi bi-people me-1"></i>Moderada</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- BALNEARIOS --}}
            <div class="tab-pane fade" id="balnearios" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 5) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/balnearios/dimas-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Cascadas de Agua Azul">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-water me-1 text-success"></i> Balnearios
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Tumbalá / Ocosingo, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Don Dimas</h5>
                                    <p class="text-muted small">Cascadas escalonadas de aguas turquesas espectaculares.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Pozas naturales</span>
                                        <span class="badge badge-eco">Natación</span>
                                        <span class="badge badge-eco">Senderos</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>4-6 h</span>
                                            <span><i class="bi bi-people me-1"></i>Fácil</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('destinos.show', 7) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                                <div class="position-relative" style="height: 220px; overflow:hidden;">
                                    <img src="{{ asset('img/balnearios/jatate-1.png') }}"
                                        class="w-100 h-100 object-fit-cover card-img-zoom"
                                        alt="Cascada Misol-Ha">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-white text-dark shadow-sm">
                                            <i class="bi bi-water me-1 text-success"></i> Balnearios
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Salto de Agua, Chiapas</p>
                                    <h5 class="fw-bold text-dark">Rio Jatate</h5>
                                    <p class="text-muted small">Caída de 35 m con cueva detrás y pozas para nadar.</p>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <span class="badge badge-eco">Caída 35 m</span>
                                        <span class="badge badge-eco">Cueva detrás</span>
                                        <span class="badge badge-eco">Pozas para nadar</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                                        <div class="d-flex gap-3">
                                            <span><i class="bi bi-clock me-1"></i>2-3 h</span>
                                            <span><i class="bi bi-people me-1"></i>Fácil</span>
                                        </div>
                                        <span class="text-success fw-semibold">Conocer más →</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
@endpush