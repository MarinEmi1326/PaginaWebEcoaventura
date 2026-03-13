@extends('layouts.app')

@section('content')
    {{-- HERO --}}
    <section class="position-relative d-flex align-items-center text-white p-0"
        style="background: url('{{ asset('img/tonina.jpeg') }}') center center / cover no-repeat; min-height: 100vh;">

        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background: linear-gradient(90deg, rgba(6,78,59,0.78) 0%, rgba(6,95,70,0.60) 45%, rgba(6,95,70,0.20) 100%);">
        </div>

        <div class="container-fluid position-relative px-lg-5 px-4">
            <div class="row">
                <div class="col-xl-7 col-lg-8">
                    <span class="badge rounded-pill bg-light bg-opacity-25 text-white px-4 py-2 mb-4 fs-6">
                        Guía digital de Ocosingo, Chiapas
                    </span>

                    <h1 class="display-1 fw-bold lh-1"
                        style="font-family: Georgia, 'Times New Roman', serif; font-size:60px;">
                        Conoce y valora <br> Ocosingo antes de <br> visitarlo
                    </h1>

                    <p class="lead mt-4 text-white" style="max-width: 850px; line-height: 1.7; font-size:18px;">
                        Una plataforma de difusión cultural y turística que te permite descubrir la riqueza natural,
                        arqueológica y comunitaria del corazón de la Selva Lacandona. Tu guía digital para planificar
                        una visita responsable.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('destinos.index') }}"
                            class="btn btn-light px-4 py-3 rounded-4 fw-semibold border-0">
                            Explorar Centros Turísticos <i class="bi bi-arrow-right ms-2"></i>
                        </a>

                        <a href="{{ route('cultura') }}" class="btn btn-outline-light px-4 py-3 rounded-4 fw-semibold">
                            Cultura y Patrimonio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CENTROS TURÍSTICOS DESTACADOS --}}
    <section id="centros" class="home-section">
        <div class="home-container">

            <div class="home-featured-header">
                <div class="home-kicker">Centros turísticos destacados</div>
                <h2 class="home-title">Tesoros naturales y culturales</h2>
                <p class="home-lead">
                    Ocosingo resguarda sitios de incomparable valor arqueológico, natural y comunitario.
                    Descubre cada uno con información detallada para planificar tu visita.
                </p>
            </div>

            <div class="row g-4">
                @foreach ($destacados as $d)
                    <div class="col-lg-4">
                        @include('centros._card', ['d' => $d])
                    </div>
                @endforeach
            </div>

            <div class="home-btn-center">
                <a href="{{ route('destinos.index') }}" class="home-btn home-btn-outline">
                    Ver todos los centros turísticos <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    {{-- CULTURA Y PATRIMONIO --}}
    <section id="cultura" class="home-section">
        <div class="home-container">
            <div class="home-cultura-grid">

                <div class="home-cultura-text">
                    <div class="home-kicker text-start">Cultura y patrimonio</div>
                    <h2 class="home-title text-start">Identidad viva de Ocosingo</h2>

                    <p class="home-lead text-start">
                        Ocosingo es mucho más que un destino natural. Es un territorio donde conviven
                        tradiciones milenarias, gastronomía auténtica, arte popular y comunidades indígenas
                        que son guardianas de un patrimonio cultural invaluable.
                    </p>


                    <a href="{{ route('cultura') }}" class="home-btn home-btn-solid mt-3">
                        Explorar cultura y patrimonio <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="home-info-list">
                    <div class="home-info-card">
                        <div class="home-info-icon">
                            <i class="bi bi-fire"></i>
                        </div>
                        <div>
                            <h3 class="home-info-title">Tradiciones Vivas</h3>
                            <p class="home-info-text">
                                Rituales y ceremonias ancestrales que las comunidades mayas y lacandonas
                                mantienen hasta hoy.
                            </p>
                        </div>
                    </div>

                    <div class="home-info-card">
                        <div class="home-info-icon">
                            <i class="bi bi-fork-knife"></i>
                        </div>
                        <div>
                            <h3 class="home-info-title">Gastronomía Regional</h3>
                            <p class="home-info-text">
                                Sabores únicos de Chiapas: tamales de chipilín, pozol, cochito horneado
                                y cacao ceremonial.
                            </p>
                        </div>
                    </div>

                    <div class="home-info-card">
                        <div class="home-info-icon">
                            <i class="bi bi-palette"></i>
                        </div>
                        <div>
                            <h3 class="home-info-title">Artesanías</h3>
                            <p class="home-info-text">
                                Textiles bordados, joyería, cerámica lacandona y tallas en madera
                                con diseños inspirados en la herencia prehispánica.
                            </p>
                        </div>
                    </div>

                    <div class="home-info-card">
                        <div class="home-info-icon">
                            <i class="bi bi-music-note-beamed"></i>
                        </div>
                        <div>
                            <h3 class="home-info-title">Festividades</h3>
                            <p class="home-info-text">
                                Celebraciones que fusionan tradiciones prehispánicas y coloniales
                                a lo largo del año.
                            </p>
                        </div>
                    </div>

                    <div class="home-info-card">
                        <div class="home-info-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h3 class="home-info-title">Comunidades Indígenas</h3>
                            <p class="home-info-text">
                                Pueblos tzeltales, tzotziles y lacandones que preservan lenguas,
                                saberes y modos de vida ancestrales.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- TURISMO RESPONSABLE --}}
    <section class="home-responsable">
        <div class="home-container">
            <div class="home-kicker text-white">Turismo responsable</div>
            <h2 class="home-title">Viaja con propósito</h2>
            <p class="home-lead">
                Conocer Ocosingo es también comprender la importancia de preservar su biodiversidad
                y respetar a las comunidades que lo habitan.
            </p>

            <div class="home-responsable-grid">
                <div class="home-value-card">
                    <div class="home-value-icon">
                        <i class="bi bi-leaf text-white"></i>
                    </div>
                    <h3 class="home-value-title">Conservación Ambiental</h3>
                    <p class="home-value-text">
                        Proteger los ecosistemas de la Selva Lacandona y promover prácticas de bajo impacto.
                    </p>
                </div>

                <div class="home-value-card">
                    <div class="home-value-icon">
                        <i class="bi bi-heart text-white"></i>
                    </div>
                    <h3 class="home-value-title">Respeto Cultural</h3>
                    <p class="home-value-text">
                        Valorar y respetar las tradiciones, lenguas y modos de vida de las comunidades locales.
                    </p>
                </div>

                <div class="home-value-card">
                    <div class="home-value-icon">
                        <i class="bi bi-flower1 text-white"></i>
                    </div>
                    <h3 class="home-value-title">Turismo Comunitario</h3>
                    <p class="home-value-text">
                        Apoyar cooperativas locales y proyectos de ecoturismo gestionados por comunidades indígenas.
                    </p>
                </div>

                <div class="home-value-card">
                    <div class="home-value-icon">
                        <i class="bi bi-shield-check text-white"></i>
                    </div>
                    <h3 class="home-value-title">Viajero Consciente</h3>
                    <p class="home-value-text">
                        Guías prácticas para que cada visitante contribuya positivamente al territorio.
                    </p>
                </div>
            </div>

            <div class="home-btn-center">
                <a href="{{ route('turismo-responsable') }}" class="home-btn home-btn-solid">
                    Aprende sobre turismo responsable <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- RUTAS SUGERIDAS --}}
    <section class="home-section">
        <div class="home-container">

            <div class="home-rutas-header">
                <div class="home-kicker">Planifica tu visita</div>
                <h2 class="home-title">Rutas sugeridas y orientación</h2>
                <p class="home-lead">
                    Organiza tu recorrido por Ocosingo con nuestras rutas recomendadas y consejos
                    según la temporada del año.
                </p>
            </div>

            <div class="home-rutas-grid">
                <div class="home-ruta-card">
                    <div class="home-ruta-top">
                        <div class="home-ruta-icon">
                            <i class="bi bi-map"></i>
                        </div>
                        <div>
                            <h3 class="home-ruta-title">Ruta Arqueológica</h3>
                            <div class="home-ruta-meta">2 días · Moderada</div>
                        </div>
                    </div>
                    <p class="home-ruta-text">
                        Toniná → Bonampak → Yaxchilán. Un recorrido por las grandes ciudades mayas.
                    </p>
                </div>

                <div class="home-ruta-card">
                    <div class="home-ruta-top">
                        <div class="home-ruta-icon">
                            <i class="bi bi-map"></i>
                        </div>
                        <div>
                            <h3 class="home-ruta-title">Ruta de Cascadas</h3>
                            <div class="home-ruta-meta">1 día · Fácil</div>
                        </div>
                    </div>
                    <p class="home-ruta-text">
                        Agua Azul → Misol-Ha. Cascadas espectaculares en un solo día.
                    </p>
                </div>

                <div class="home-ruta-card">
                    <div class="home-ruta-top">
                        <div class="home-ruta-icon">
                            <i class="bi bi-map"></i>
                        </div>
                        <div>
                            <h3 class="home-ruta-title">Ruta Comunitaria</h3>
                            <div class="home-ruta-meta">3 días · Alta</div>
                        </div>
                    </div>
                    <p class="home-ruta-text">
                        Comunidades lacandonas → Selva → Laguna Miramar. Inmersión cultural total.
                    </p>
                </div>
            </div>

            <div class="home-season-grid">
                <div class="home-season-card">
                    <div class="home-season-icon">
                        <i class="bi bi-sun"></i>
                    </div>
                    <div>
                        <div class="home-season-title">
                            Seca (Nov-Abr)
                            <span class="home-season-badge">Recomendada</span>
                        </div>
                        <p class="home-season-text">
                            Ideal para senderismo y cascadas.
                        </p>
                    </div>
                </div>

                <div class="home-season-card">
                    <div class="home-season-icon">
                        <i class="bi bi-cloud-rain"></i>
                    </div>
                    <div>
                        <div class="home-season-title">Lluvias (May-Oct)</div>
                        <p class="home-season-text">
                            Selva exuberante, menos visitantes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="home-btn-center">
                <a href="{{ route('ruta') }}" class="home-btn home-btn-outline">
                    Ver todas las rutas <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>
@endsection
