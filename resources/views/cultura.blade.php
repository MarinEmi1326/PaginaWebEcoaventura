@extends('layouts.app')

@section('title', 'Cultura y Patrimonio')

@section('content')
<div class="eco-page py-4 py-lg-5">
    
    <div class="eco-wrap">

        <div class="text-center mb-5">
            <div class="eco-kicker mb-2">PATRIMONIO CULTURAL</div>
            <h1 class="eco-title display-4 mb-3">Cultura y Patrimonio de Ocosingo</h1>
            <p class="eco-lead">
                Descubre la riqueza cultural de un territorio donde convergen tradiciones milenarias,
                gastronomía auténtica, artesanía ancestral y comunidades indígenas que son guardianas de un legado invaluable.
            </p>
        </div>

        @php
            $sections = [
                [
                    'icon' => 'bi bi-fire',
                    'title' => 'Tradiciones y Ceremonias',
                    'text' => 'Ocosingo, Chiapas, destaca por una rica mezcla de tradiciones mayas tzeltales y cultura mestiza, destacando las fiestas patronales de San Jacinto de Polonia (17 de agosto) y la Candelaria (2 de febrero). Se celebran con misas, música de marimba, ferias, y ceremonias ancestrales pidiendo por las cosechas y la lluvia.',
                    'items' => [
                        'Fiesta de San Jacinto de Polonia (17 de agosto)',
                        'Fiesta de la Candelaria (2 de febrero)',
                        'Ceremonias de la Santa Cruz (3 de mayo)',
                        'Tradiciones Tzeltales',
                        'Día de Muertos',
                        'Fiestas Patrias'
                    ]
                ],
                [
                    'icon' => 'bi bi-fork-knife',
                    'title' => 'Gastronomía',
                    'text' => 'La comida típica de Ocosingo, Chiapas, destaca por su tradición láctea y sabores de la Selva Lacandona, siendo el famoso queso de bola de Ocosingo (artesanal y relleno de carne) su mayor exponente. Otros platillos emblemáticos incluyen el cochito horneado, tamales de chipilín, sopa de pan, tasajo con pepita y los tradicionales dulces chimbos.',
                    'items' => [
                        'Queso de Bola de Ocosingo',
                        'Cochito Horneado',
                        'Tamales Chiapanecos',
                        'Cochito horneado y tasajo chiapaneco',
                        'Sopa de Pan',
                        'Chimbos',
                        'Tasajo con Pepita'
                    ]
                ],
                [
                    'icon' => 'bi bi-palette',
                    'title' => 'Artesanías',
                    'text' => 'Las artesanías de Ocosingo, Chiapas, destacan por su rica tradición textil elaborada en telar de cintura, incluyendo huipiles, blusas bordadas y bolsas de estambre con pompones. También sobresalen los trabajos en madera, la joyería de ámbar, el crochet, y la famosa producción local de quesos artesanales, como el queso de bola.',
                    'items' => [
                        'Textiles y Bordados',
                        'Artesanías en Madera y Materiales Naturales',
                        'Joyería y Accesorios',
                        'Quesos Artesanales',
                        'Puntos de Venta',
                    ]
                ],
                [
                    'icon' => 'bi bi-music-note',
                    'title' => 'Festividades',
                    'text' => 'El calendario festivo de Ocosingo refleja la fusión de tradiciones prehispánicas y coloniales. Cada celebración es una ventana a la identidad comunitaria y una oportunidad para vivir la cultura local.',
                    'items' => [
                        'Fiesta de Santo Domingo de Guzmán (4 de agosto) – fiesta patronal',
                        'Carnaval de Ocosingo – danzas y música tradicional',
                        'Día de Muertos – altares y ofrendas en comunidades',
                        'Festival del Queso Bola – celebración gastronómica',
                        'Ceremonia del Año Nuevo Maya',
                    ]
                ],
                [
                    'icon' => 'bi bi-people',
                    'title' => 'Comunidades Indígenas',
                    'text' => 'Ocosingo es hogar de pueblos originarios que preservan lenguas, saberes y formas de organización social propias. Los tzeltales, tzotziles y lacandones mantienen una relación profunda con el territorio que habitan.',
                    'items' => [
                        'Comunidades lacandones de Nahá y Metzabok – guardianes de la selva',
                        'Pueblos tzeltales – la etnia más numerosa de la región',
                        'Cooperativas de café orgánico y miel de comunidades indígenas',
                        'Proyectos de ecoturismo comunitario autogestionados',
                        'Centros de medicina tradicional y herbolaria maya',
                    ]
                ],
            ];
        @endphp

        @foreach($sections as $sec)
            <section class="eco-section mb-5">

                {{-- Header alineado: icono + título --}}
                <div class="eco-head">
                    <div class="eco-iconbox">
                        <i class="{{ $sec['icon'] }}"></i>
                    </div>
                    <h2 class="eco-h2 mb-0">{{ $sec['title'] }}</h2>
                </div>

                {{-- Texto alineado con el título --}}
                <p class="eco-paragraph eco-indent mb-0">{{ $sec['text'] }}</p>

                <div class="eco-items">
                    @foreach($sec['items'] as $item)
                        <div class="eco-pill">
                            <span class="eco-pin">
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <span class="eco-pill-text">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>

            </section>
        @endforeach

    </div>
</div>
@endsection