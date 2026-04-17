@extends('layouts.app')

@section('title', 'Cultura y Patrimonio')

@section('content')
    <div class="container py-5">
        <!-- Hero / Título principal -->
        <div class="text-center mb-5 pb-4">
            <p class="lead text-success fw-semibold fs-4 mb-2">PATRIMONIO CULTURAL</p>
            <h1 class="display-4 fw-bold text-dark mb-3">Cultura y Patrimonio de Ocosingo</h1>
            <p class="fs-5 text-muted mx-auto" style="max-width: 800px;">
                Descubre la riqueza cultural de un territorio donde convergen tradiciones milenarias,
                gastronomía auténtica, artesanía ancestral y comunidades indígenas que son guardianas de un legado
                invaluable.
            </p>
        </div>

        <!-- Secciones con cards (mismo estilo que Turismo Responsable) -->
        <div class="row justify-content-center g-5">

            <!-- 1. Tradiciones y Ceremonias -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-fire text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Tradiciones y Ceremonias</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            Ocosingo, Chiapas, destaca por una rica mezcla de tradiciones mayas tzeltales y cultura mestiza,
                            destacando las fiestas patronales de San Jacinto de Polonia (17 de agosto) y la Candelaria (2 de
                            febrero). Se celebran con misas, música de marimba, ferias, y ceremonias ancestrales pidiendo
                            por las cosechas y la lluvia.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Fiesta de San Jacinto de Polonia (17 de agosto)
                            </li>
                            <li class="list-group-item border-0 ps-0 py-3">Fiesta de la Candelaria (2 de febrero)</li>
                            <li class="list-group-item border-0 ps-0 py-3">Ceremonias de la Santa Cruz (3 de mayo)</li>
                            <li class="list-group-item border-0 ps-0 py-3">Tradiciones Tzeltales</li>
                            <li class="list-group-item border-0 ps-0 py-3">Día de Muertos</li>
                            <li class="list-group-item border-0 ps-0 py-3">Fiestas Patrias</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 2. Gastronomía -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-fork-knife text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Gastronomía</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            La comida típica de Ocosingo, Chiapas, destaca por su tradición láctea y sabores de la Selva
                            Lacandona, siendo el famoso queso de bola de Ocosingo (artesanal y relleno de carne) su mayor
                            exponente. Otros platillos emblemáticos incluyen el cochito horneado, tamales de chipilín, sopa
                            de pan, tasajo con pepita y los tradicionales dulces chimbos.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Queso de Bola de Ocosingo</li>
                            <li class="list-group-item border-0 ps-0 py-3">Cochito Horneado</li>
                            <li class="list-group-item border-0 ps-0 py-3">Tamales Chiapanecos</li>
                            <li class="list-group-item border-0 ps-0 py-3">Sopa de Pan</li>
                            <li class="list-group-item border-0 ps-0 py-3">Chimbos</li>
                            <li class="list-group-item border-0 ps-0 py-3">Tasajo con Pepita</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 3. Artesanías -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-palette text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Artesanías</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            Las artesanías de Ocosingo, Chiapas, destacan por su rica tradición textil elaborada en telar de
                            cintura, incluyendo huipiles, blusas bordadas y bolsas de estambre con pompones. También
                            sobresalen los trabajos en madera, la joyería de ámbar, el crochet, y la famosa producción local
                            de quesos artesanales, como el queso de bola.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Textiles y Bordados</li>
                            <li class="list-group-item border-0 ps-0 py-3">Artesanías en Madera y Materiales Naturales</li>
                            <li class="list-group-item border-0 ps-0 py-3">Joyería y Accesorios</li>
                            <li class="list-group-item border-0 ps-0 py-3">Quesos Artesanales</li>
                            <li class="list-group-item border-0 ps-0 py-3">Puntos de Venta</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 4. Festividades -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-music-note text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Festividades</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            El calendario festivo de Ocosingo refleja la fusión de tradiciones prehispánicas y coloniales.
                            Cada celebración es una ventana a la identidad comunitaria y una oportunidad para vivir la
                            cultura local.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Fiesta de Santo Domingo de Guzmán (4 de agosto) –
                                fiesta patronal</li>
                            <li class="list-group-item border-0 ps-0 py-3">Carnaval de Ocosingo – danzas y música
                                tradicional</li>
                            <li class="list-group-item border-0 ps-0 py-3">Día de Muertos – altares y ofrendas en
                                comunidades</li>
                            <li class="list-group-item border-0 ps-0 py-3">Festival del Queso Bola – celebración
                                gastronómica</li>
                            <li class="list-group-item border-0 ps-0 py-3">Ceremonia del Año Nuevo Maya</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- 5. Comunidades Indígenas -->
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="rounded-4 fs-3 p-4 me-4 d-flex align-items-center justify-content-center"
                                style="width: 70px; height: 70px; background-color: #e9ecef;">
                                <i class="bi bi-people text-success"></i>
                            </span>
                            <h2 class="card-title mb-0 fw-bold">Comunidades Indígenas</h2>
                        </div>
                        <p class="text-muted mb-4 fs-5">
                            Ocosingo es hogar de pueblos originarios que preservan lenguas, saberes y formas de organización
                            social propias. Los tzeltales, tzotziles y lacandones mantienen una relación profunda con el
                            territorio que habitan.
                        </p>
                        <ol class="list-group list-group-numbered custom-list">
                            <li class="list-group-item border-0 ps-0 py-3">Comunidades lacandones de Nahá y Metzabok –
                                guardianes de la selva</li>
                            <li class="list-group-item border-0 ps-0 py-3">Pueblos tzeltales – la etnia más numerosa de la
                                región</li>
                            <li class="list-group-item border-0 ps-0 py-3">Cooperativas de café orgánico y miel de
                                comunidades indígenas</li>
                            <li class="list-group-item border-0 ps-0 py-3">Proyectos de ecoturismo comunitario
                                autogestionados</li>
                            <li class="list-group-item border-0 ps-0 py-3">Centros de medicina tradicional y herbolaria maya
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
