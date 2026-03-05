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
                <li class="breadcrumb-item active">Zona Arqueológica de Toniná</li>
            </ol>
        </div>
    </nav>

    {{-- GALERÍA --}}
    <section class="pb-4">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="rounded-4 overflow-hidden" style="height: 420px;">
                        <img src="{{ asset('img/turisticos/tonina-1.png') }}" class="w-100 h-100 object-fit-cover"
                            alt="Zona Arqueológica de Toniná">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-column gap-3 h-100">
                        <div class="rounded-4 overflow-hidden flex-fill" style="min-height: 200px;">
                            <img src="{{ asset('img/turisticos/tonina-1.png') }}" class="w-100 h-100 object-fit-cover"
                                alt="Toniná 2">
                        </div>
                        <div class="rounded-4 overflow-hidden flex-fill" style="min-height: 200px;">
                            <img src="{{ asset('img/turisticos/tonina-1.png') }}" class="w-100 h-100 object-fit-cover"
                                alt="Toniná 3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENIDO --}}
    <section class="pb-5">
        <div class="container">

            {{-- Badge + Título --}}
            <div class="mb-4">
                <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                    <span class="badge rounded-pill px-3 py-2" style="background:#e2ece9; color:#1F6B4B; font-weight:500;">
                        <i class="bi bi-building me-1"></i>Zona Arqueológica
                    </span>
                    <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Ocosingo, Chiapas</span>
                </div>
                <h1 class="fw-bold display-5 mb-0">Zona Arqueológica de Toniná</h1>
            </div>

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="rounded-3 text-center p-3" style="background:#f1f5f2;">
                        <i class="bi bi-clock text-success fs-5 mb-1 d-block"></i>
                        <p class="small text-muted mb-0">Duración</p>
                        <p class="fw-semibold small mb-0">3-4 horas</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="rounded-3 text-center p-3" style="background:#f1f5f2;">
                        <i class="bi bi-compass text-success fs-5 mb-1 d-block"></i>
                        <p class="small text-muted mb-0">Dificultad</p>
                        <p class="fw-semibold small mb-0">Moderada</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="rounded-3 text-center p-3" style="background:#f1f5f2;">
                        <i class="bi bi-sun text-success fs-5 mb-1 d-block"></i>
                        <p class="small text-muted mb-0">Mejor época</p>
                        <p class="fw-semibold small mb-0">Nov - Abr</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="rounded-3 text-center p-3" style="background:#f1f5f2;">
                        <i class="bi bi-ticket-perforated text-success fs-5 mb-1 d-block"></i>
                        <p class="small text-muted mb-0">Costo aprox.</p>
                        <p class="fw-semibold small mb-0">$85 MXN</p>
                    </div>
                </div>
            </div>

            {{-- TABS --}}
            <div class="rounded-3 p-1 mb-4" style="background:#e8ede9;">
                <ul class="nav" id="detalleTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-descripcion"
                            type="button">Descripción</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-practica"
                            type="button">Info Práctica</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-recomendaciones"
                            type="button">Recomendaciones</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" data-bs-toggle="tab" data-bs-target="#tab-mapa" type="button">
                            <i class="bi bi-map me-1"></i>Ubicación
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content mb-5" id="detalleTabContent">

                {{-- DESCRIPCIÓN --}}
                <div class="tab-pane fade show active" id="tab-descripcion" role="tabpanel">
                    <p class="lh-lg mb-4">
                        Toniná, cuyo nombre significa "Casa de Piedra" en tzeltal, fue una poderosa ciudad-estado maya
                        que floreció entre los siglos VI y IX d.C. Su acrópolis de siete plataformas alcanza los 75 metros
                        de altura, superando en elevación a la Pirámide del Sol en Teotihuacán.
                    </p>
                    <p class="lh-lg mb-4">
                        El sitio incluye templos, palacios, juegos de pelota y una rica colección de esculturas y relieves
                        que narran la historia política y militar de la ciudad. El museo de sitio exhibe piezas únicas
                        como el mural de estuco de los Cuatro Soles.
                    </p>
                    <div class="rounded-3 p-4" style="background:#f1f5f2;">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-leaf me-2 text-success"></i>Valor cultural y ambiental
                        </h6>
                        <p class="text-muted mb-0">
                            Centro político y ceremonial maya que rivalizó con Palenque. Sus relieves y esculturas son
                            testimonio invaluable de la cosmovisión y organización social maya del período Clásico.
                        </p>
                    </div>
                </div>

                {{-- INFO PRÁCTICA --}}
                <div class="tab-pane fade" id="tab-practica" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-clock me-2 text-success"></i>Horarios</h6>
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-1"><i class="bi bi-dot"></i>Martes a domingo</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>9:00 AM - 5:00 PM</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Cerrado lunes y días festivos</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-ticket-perforated me-2 text-success"></i>Costos</h6>
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-1"><i class="bi bi-dot"></i>General: $85 MXN</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Estudiantes y maestros: Gratis</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Adultos mayores (INAPAM): Gratis</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-car-front me-2 text-success"></i>Como llegar</h6>
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-1"><i class="bi bi-dot"></i>Desde Ocosingo: 14 km por carretera</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Taxi colectivo desde el mercado central</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Señalización desde la ciudad</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-shield-check me-2 text-success"></i>Servicios</h6>
                            <ul class="list-unstyled text-muted small">
                                <li class="mb-1"><i class="bi bi-dot"></i>Museo de sitio incluido</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Guías locales disponibles</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Estacionamiento gratuito</li>
                                <li class="mb-1"><i class="bi bi-dot"></i>Sanitarios en sitio</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- RECOMENDACIONES --}}
                {{-- RECOMENDACIONES --}}
                <div class="tab-pane fade" id="tab-recomendaciones" role="tabpanel">
                    <div class="d-flex flex-column gap-3">
                        <div class="rounded-3 p-3" style="background:#f1f5f2;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-exclamation-triangle text-success fs-5"></i>
                                <span>Llegar temprano para evitar el calor intenso</span>
                            </div>
                        </div>
                        <div class="rounded-3 p-3" style="background:#f1f5f2;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-exclamation-triangle text-success fs-5"></i>
                                <span>Llevar agua, protector solar y calzado comodo</span>
                            </div>
                        </div>
                        <div class="rounded-3 p-3" style="background:#f1f5f2;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-exclamation-triangle text-success fs-5"></i>
                                <span>Contratar guia local para comprender la historia</span>
                            </div>
                        </div>
                        <div class="rounded-3 p-3" style="background:#f1f5f2;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-exclamation-triangle text-success fs-5"></i>
                                <span>Visitar el museo de sitio antes de recorrer las ruinas</span>
                            </div>
                        </div>
                        <div class="rounded-3 p-3" style="background:#f1f5f2;">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-exclamation-triangle text-success fs-5"></i>
                                <span>Respetar las areas restringidas y no subir a estructuras fragiles</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MAPA --}}
                <div class="tab-pane fade" id="tab-mapa" role="tabpanel">
                    <div class="rounded-4 overflow-hidden" style="height: 380px;">
                        <iframe width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                           src="https://www.google.com/maps/embed/v1/place?key={{ env('GOOGLE_MAPS_API_KEY') }}&q=Zona+Arqueologica+Tonina,Ocosingo,Chiapas,Mexico&zoom=13">
                        </iframe>
                    </div>
                    <p class="small text-muted mt-2">
                        <i class="bi bi-geo-alt me-1 text-success"></i>
                        Carretera Ocosingo-Altamirano Km 14, Chiapas, Mexico
                    </p>
                </div>

            </div>

            {{-- BOTONES --}}
            <div class="d-flex flex-wrap gap-3 mb-5 pt-3 border-top">
                <button class="btn btn-outline-success rounded-3 px-4 py-2">
                    <i class="bi bi-envelope me-2"></i>Solicitar información
                </button>
                <a href="{{ route('login') }}" class="btn btn-success rounded-3 px-4 py-2">
                    <i class="bi bi-send me-2"></i>Solicitar reservación o interés
                </a>
            </div>

            {{-- RESEÑAS --}}
            <div class="mb-5">
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-chat-square-text me-2 text-success"></i>Comentarios y Calificaciones
                </h5>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="text-warning small">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <span class="fw-semibold">4.5</span>
                    <span class="text-muted small">(2 reseñas)</span>
                </div>

                <div class="rounded-3 p-4 mb-4 text-center" style="background:#f1f5f2;">
                    <p class="text-muted small mb-2">Inicia sesión como Turista para comentar y calificar</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">
                        <i class="bi bi-person me-1"></i>Iniciar Sesión
                    </a>
                </div>

                <div class="d-flex flex-column gap-3">
                    <div class="rounded-3 p-4" style="background:#f1f5f2;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                    style="width:36px; height:36px; background:#d1d9d4; color:#1F6B4B; font-size:0.85rem;">
                                    A</div>
                                <div>
                                    <p class="fw-semibold mb-0 small">Ana García</p>
                                    <p class="text-muted mb-0" style="font-size:0.75rem;">2026-02-10</p>
                                </div>
                            </div>
                            <div class="text-warning small">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Un lugar increible, la historia que se respira es impresionante.
                            Totalmente recomendado.</p>
                    </div>

                    <div class="rounded-3 p-4" style="background:#f1f5f2;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                    style="width:36px; height:36px; background:#d1d9d4; color:#1F6B4B; font-size:0.85rem;">
                                    R</div>
                                <div>
                                    <p class="fw-semibold mb-0 small">Roberto Díaz</p>
                                    <p class="text-muted mb-0" style="font-size:0.75rem;">2026-02-05</p>
                                </div>
                            </div>
                            <div class="text-warning small">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Muy buena experiencia. Los guias locales hacen la diferencia.
                            Llevar agua y protector solar.</p>
                    </div>
                </div>
            </div>

            {{-- SERVICIOS RELACIONADOS --}}
            <div class="rounded-3 p-4 mb-5" style="background:#f1f5f2;">
                <h6 class="fw-bold mb-3">Servicios relacionados</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 bg-white">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-house-heart fs-5 text-success"></i>
                                <p class="fw-semibold mb-0">Hospedaje cercano</p>
                            </div>
                            <p class="text-muted small mb-0">Cabanas ecologicas y hospedaje comunitario disponible en la
                                zona.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rounded-3 p-3 bg-white">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-cup-hot fs-5 text-success"></i>
                                <p class="fw-semibold mb-0">Restaurantes</p>
                            </div>
                            <p class="text-muted small mb-0">Comedores locales con gastronomia regional chiapaneca.</p>
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