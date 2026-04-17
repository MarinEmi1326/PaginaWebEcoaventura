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
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-leaf text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Conservación Ambiental</h2>
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
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-heart text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Respeto Cultural</h2>
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
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-people text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Turismo Comunitario</h2>
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
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-recycle text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Prácticas Sostenibles</h2>
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
@endsection
