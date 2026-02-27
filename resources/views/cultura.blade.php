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
                    'text' => 'Ocosingo conserva rituales y costumbres ancestrales que las comunidades mayas practican como parte viva de su identidad. Las ceremonias de agradecimiento a la tierra, los rituales con copal y las ofrendas en cuevas sagradas son expresiones de una cosmovisión milenaria que sigue vigente.',
                    'items' => [
                        'Ceremonias del fuego lacandón en Nahá y Metzabok',
                        'Rituales de siembra y cosecha en comunidades tzeltales',
                        'Procesiones religiosas sincréticas durante Semana Santa',
                        'Danzas tradicionales del Carnaval zoque-tzeltal',
                    ]
                ],
                [
                    'icon' => 'bi bi-fork-knife',
                    'title' => 'Gastronomía Regional',
                    'text' => 'La cocina de Ocosingo es un patrimonio vivo que fusiona sabores prehispánicos con influencias coloniales. Los ingredientes locales como el cacao, el chipilín, el maíz criollo y las hierbas de la selva crean una experiencia gastronómica única e irrepetible.',
                    'items' => [
                        'Queso bola de Ocosingo – denominación de origen',
                        'Tamales de chipilín y de bola',
                        'Pozol – bebida ceremonial de maíz y cacao',
                        'Cochito horneado y tasajo chiapaneco',
                        'Cacao ceremonial y chocolate artesanal',
                    ]
                ],
                [
                    'icon' => 'bi bi-palette',
                    'title' => 'Artesanías',
                    'text' => 'Las manos de artesanos tzeltales, tzotziles y lacandones transforman materias primas naturales en obras de arte funcional. Cada pieza lleva consigo siglos de técnica heredada y simbolismo cultural profundo.',
                    'items' => [
                        'Textiles bordados con diseños cosmogónicos mayas',
                        'Joyería de ámbar chiapaneco – resina fósil milenaria',
                        'Cerámica lacandona con formas de deidades',
                        'Tallas en madera y máscaras ceremoniales',
                        'Hamacas y bolsas de fibra natural',
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