@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <!-- Hero / Título principal -->
        <div class="text-center mb-5 pb-4">
            <p class="lead text-success fw-semibold fs-4 mb-2">VIAJA CON PROPÓSITO</p>
            <h1 class="display-4 fw-bold text-dark mb-3">Turismo Responsable en Ocosingo</h1>
            <p class="fs-5 text-muted mx-auto" style="max-width: 800px;">
                Aprende cómo tu visita puede contribuir positivamente a la conservación ambiental, el respeto cultural
                y el desarrollo de las comunidades locales.
            </p>
        </div>

        <!-- Secciones con cards -->
        <div class="row justify-content-center g-5">

            <!-- 1. Conservación Ambiental -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span
                                class="badge bg-success rounded-pill fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-leaf"></i>
                            </span>
                            <h2 class="card-title mb-0 text-success fw-bold">Conservación Ambiental</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            La Selva Lacandona y los ecosistemas de Ocosingo son frágiles. Cada visitante tiene la
                            responsabilidad de minimizar su huella ecológica.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">No dejar basura en senderos ni cuerpos de agua
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Usar protector solar y repelente biodegradable
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">No extraer plantas, animales, piedras ni
                                artefactos</li>
                            <li class="list-group-item border-0 ps-0 py-3">Respetar los senderos marcados y áreas
                                restringidas</li>
                            <li class="list-group-item border-0 ps-0 py-3">Preferir transporte compartido o colectivo</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 2. Respeto Cultural -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span
                                class="badge bg-success rounded-pill fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-heart"></i>
                            </span>
                            <h2 class="card-title mb-0 text-success fw-bold">Respeto Cultural</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            Las comunidades indígenas de la región preservan lenguas, tradiciones y modos de vida que
                            merecen respeto absoluto.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Pedir permiso antes de fotografiar personas o
                                rituales</li>
                            <li class="list-group-item border-0 ps-0 py-3">Aprender frases básicas en tzeltal o lacandón
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">No comparar ni juzgar prácticas culturales
                                diferentes</li>
                            <li class="list-group-item border-0 ps-0 py-3">Respetar los espacios sagrados y ceremoniales
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Escuchar y aprender de los guías comunitarios
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 3. Turismo Comunitario -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span
                                class="badge bg-success rounded-pill fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-people"></i>
                            </span>
                            <h2 class="card-title mb-0 text-success fw-bold">Turismo Comunitario</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            El turismo comunitario permite que los beneficios económicos lleguen directamente a las
                            comunidades locales.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Contratar guías y servicios ofrecidos por
                                cooperativas locales</li>
                            <li class="list-group-item border-0 ps-0 py-3">Comprar artesanías directamente a los artesanos
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Hospedarse en cabañas y alojamientos comunitarios
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Consumir alimentos preparados con ingredientes
                                locales</li>
                            <li class="list-group-item border-0 ps-0 py-3">Participar en talleres y experiencias que las
                                comunidades ofrecen</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 4. Prácticas Sostenibles -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span
                                class="badge bg-success rounded-pill fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-recycle"></i>
                            </span>
                            <h2 class="card-title mb-0 text-success fw-bold">Prácticas Sostenibles</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            Pequeñas acciones individuales generan un gran impacto colectivo en la preservación del
                            territorio.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Llevar botella reutilizable y bolsas de tela</li>
                            <li class="list-group-item border-0 ps-0 py-3">Separar residuos según las indicaciones locales
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Reducir el consumo de plásticos de un solo uso
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Preferir productos locales y orgánicos</li>
                            <li class="list-group-item border-0 ps-0 py-3">Compartir tu experiencia de turismo responsable
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Sección Cooperativas y Proyectos Comunitarios -->
    <div class="mt-4">
        <h2 class="text-center fw-bold text-success mb-4">Cooperativas y Proyectos Comunitarios</h2>
        <p class="text-center text-muted fs-5 mb-5" style="max-width: 800px; margin: 0 auto;">
            Conoce las organizaciones locales que hacen posible un turismo que beneficia directamente a las comunidades de
            Ocosingo.
        </p>

        <div class="row g-4 justify-content-center">

            <!-- Cooperativa Lacandón de Nahá -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <span
                                class="badge bg-success rounded-pill fs-4 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-people"></i>
                            </span>
                            <h5 class="card-title mb-0 text-success fw-bold">
                                Cooperativa Lacandón de Nahá
                            </h5>
                        </div>
                        <p class="card-text text-muted mb-3 flex-grow-1">
                            Guías comunitarios y hospedaje ecológico en la selva. Ofrecen recorridos por lagunas,
                            cuevas y centros ceremoniales.
                        </p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-success btn-sm rounded-pill">
                                Ecoturismo y preservación cultural
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Café orgánico Tzeltal -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <span
                                class="badge bg-success rounded-pill fs-4 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-people"></i>
                            </span>
                            <h5 class="card-title mb-0 text-success fw-bold">
                                Café orgánico Tzeltal
                            </h5>
                        </div>
                        <p class="card-text text-muted mb-3 flex-grow-1">
                            Cooperativa de productores de café orgánico de altura. Ofrecen tours por las fincas
                            y talleres de cata.
                        </p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-success btn-sm rounded-pill">
                                Agricultura sustentable
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artesanas de Ocosingo -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <span
                                class="badge bg-success rounded-pill fs-4 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-people"></i>
                            </span>
                            <h5 class="card-title mb-0 text-success fw-bold">
                                Artesanas de Ocosingo
                            </h5>
                        </div>
                        <p class="card-text text-muted mb-3 flex-grow-1">
                            Colectivo de mujeres tzeltales que producen textiles bordados a mano con tintes
                            naturales y diseños ancestrales.
                        </p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-success btn-sm rounded-pill">
                                Artesanía y empoderamiento
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardianes de Miramar -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <span
                                class="badge bg-success rounded-pill fs-4 p-3 me-3 d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-people"></i>
                            </span>
                            <h5 class="card-title mb-0 text-success fw-bold">
                                Guardianes de Miramar
                            </h5>
                        </div>
                        <p class="card-text text-muted mb-3 flex-grow-1">
                            Comunidad que gestiona el acceso a la Laguna Miramar, protegiendo el ecosistema
                            y ofreciendo guías especializados.
                        </p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-success btn-sm rounded-pill">
                                Conservación y turismo
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
   
@endsection
