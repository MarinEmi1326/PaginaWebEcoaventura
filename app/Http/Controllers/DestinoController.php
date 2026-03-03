<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DestinoController extends Controller
{
    public function index(Request $request, $tipo = null)
    {
        $cards = [
            [
                'slug' => 'mirador-natural',
                'categoria_slug' => 'ecoturisticos',
                'tipo' => 'Ecoturístico',
                'img' => asset('img/ecoturisticos/miramar-2.png'),
                'titulo' => 'Mirador Natural',
                'descripcion' => 'Vista panorámica rodeada de selva.',
                'precio' => 150,
                'rating' => 4.8,
                'reviews' => 210,
                'chips' => ['Senderismo', 'Guías', 'Fotos'],
                'ubicacion' => 'Ocosingo, Chiapas',
                'url' => route('destinos.show', 'mirador-natural'),
            ],
            [
                'slug' => 'balneario-encanto',
                'categoria_slug' => 'balnearios',
                'tipo' => 'Balneario',
                'img' => asset('img/balnearios/encanto-1.png'),
                'titulo' => 'Balneario Encanto',
                'descripcion' => 'Aguas cristalinas, ideal para familia.',
                'precio' => 120,
                'rating' => 4.6,
                'reviews' => 98,
                'chips' => ['Alberca', 'Familiar', 'Comida'],
                'ubicacion' => 'Tumbalá, Chiapas',
                'url' => route('destinos.show', 'balneario-encanto'),
            ],
            [
                'slug' => 'zona-turistica-ayutla',
                'categoria_slug' => 'turisticos',
                'tipo' => 'Turístico',
                'img' => asset('img/turisticos/ayutla-2.png'),
                'titulo' => 'Zona Turística',
                'descripcion' => 'Recorrido cultural con actividades.',
                'precio' => 200,
                'rating' => 4.9,
                'reviews' => 312,
                'chips' => ['Cultura', 'Recorridos', 'Artesanías'],
                'ubicacion' => 'Ocosingo, Chiapas',
                'url' => route('destinos.show', 'zona-turistica-ayutla'),
            ],
        ];

        // Buscar por texto
        $q = trim((string) $request->get('q', ''));
        if ($q !== '') {
            $t = mb_strtolower($q);
            $cards = array_values(array_filter($cards, function ($c) use ($t) {
                return str_contains(mb_strtolower($c['titulo']), $t)
                    || str_contains(mb_strtolower($c['descripcion']), $t)
                    || str_contains(mb_strtolower($c['tipo']), $t)
                    || str_contains(mb_strtolower($c['ubicacion']), $t);
            }));
        }

        // Filtrar por categoría desde URL /destinos/{tipo}
        if ($tipo) {
            $cards = array_values(array_filter($cards, fn ($c) => $c['categoria_slug'] === $tipo));
        }

        return view('destinos.index', compact('cards', 'tipo', 'q'));
    }

    // ✅ DETALLE /destino/{slug}
    public function show($slug)
    {
        $detalles = [

            'mirador-natural' => [
                'titulo' => 'Mirador Natural',
                'categoria' => 'Ecoturismo',
                'ubicacion' => 'Ocosingo, Chiapas',

                'galeria' => [
                    asset('img/ecoturisticos/miramar-1.png'),
                    asset('img/ecoturisticos/miramar-2.png'),
                    asset('img/ecoturisticos/miramar-1.png'),
                ],

                'stats' => [
                    ['icon' => 'bi-clock', 'label' => 'Duración', 'value' => '3-4 horas'],
                    ['icon' => 'bi-compass', 'label' => 'Dificultad', 'value' => 'Moderada'],
                    ['icon' => 'bi-camera', 'label' => 'Mejor época', 'value' => 'Noviembre a Abril (temporada seca)'],
                    ['icon' => 'bi-cash-coin', 'label' => 'Costo aprox.', 'value' => '$150 MXN (entrada general)'],
                ],

                // ✅ IMPORTANTE: tabs con estructura (NO texto)
                'tabs' => [
                    'descripcion' => [
                        'texto' => "Este mirador ofrece vistas panorámicas rodeadas de selva. Ideal para caminar por senderos, tomar fotografías y conectar con la naturaleza.",
                        'highlight' => [
                            'titulo' => 'Valor cultural y ambiental',
                            'texto' => 'Zona importante por su biodiversidad y su aportación al turismo responsable. Conservar el área ayuda a proteger especies y ecosistemas locales.',
                        ],
                    ],
                    'info' => [
                        'cards' => [
                            ['label' => 'Horario', 'value' => 'Lunes a Domingo, 8:00 - 17:00'],
                            ['label' => 'Costo Aproximado', 'value' => '$150 MXN (entrada general)'],
                            ['label' => 'Acceso', 'value' => '14 km al este de Ocosingo por carretera pavimentada'],
                            ['label' => 'Servicios', 'chips' => ['Estacionamiento', 'Sanitarios', 'Guías locales', 'Zona de descanso']],
                        ],
                    ],
                    'recomendaciones' => [
                        'items' => [
                            'Llegar temprano para evitar el calor intenso',
                            'Llevar agua, protector solar y calzado cómodo',
                            'Contratar guía local para comprender mejor el entorno',
                            'Respetar áreas restringidas y no dejar basura',
                        ],
                    ],
                    'ubicacion' => 'Ocosingo, Chiapas. Acceso principal señalizado desde la carretera.',
                ],

                'rating' => 4.8,
                'reviews_count' => 210,

                'comentarios' => [
                    ['nombre' => 'Ana García', 'fecha' => '2026-02-10', 'stars' => 5, 'texto' => 'Un lugar increíble, la vista es impresionante. Totalmente recomendado.'],
                    ['nombre' => 'Roberto Díaz', 'fecha' => '2026-02-05', 'stars' => 4, 'texto' => 'Muy buena experiencia. Los guías locales hacen la diferencia. Llevar agua y protector solar.'],
                ],

                'servicios' => [
                    ['icon' => 'bi-building', 'titulo' => 'Hospedaje cercano', 'desc' => 'Cabañas ecológicas y hospedaje comunitario disponible en la zona.'],
                    ['icon' => 'bi-shop', 'titulo' => 'Restaurantes', 'desc' => 'Comedores locales con gastronomía regional chiapaneca.'],
                ],
            ],

            'balneario-encanto' => [
                'titulo' => 'Balneario Encanto',
                'categoria' => 'Balneario',
                'ubicacion' => 'Tumbalá, Chiapas',

                'galeria' => [
                    asset('img/balnearios/encanto-1.png'),
                    asset('img/balnearios/dimas-1.png'),
                    asset('img/balnearios/encanto-2.png'),
                ],

                'stats' => [
                    ['icon' => 'bi-clock', 'label' => 'Duración', 'value' => '3-5 horas'],
                    ['icon' => 'bi-compass', 'label' => 'Dificultad', 'value' => 'Baja'],
                    ['icon' => 'bi-camera', 'label' => 'Mejor época', 'value' => 'Todo el año'],
                    ['icon' => 'bi-cash-coin', 'label' => 'Costo aprox.', 'value' => '$120 MXN (entrada general)'],
                ],

                'tabs' => [
                    'descripcion' => [
                        'texto' => "Balneario con aguas cristalinas, áreas para descanso y zonas familiares. Perfecto para pasar el día con tranquilidad y seguridad.",
                        'highlight' => [
                            'titulo' => 'Valor cultural y ambiental',
                            'texto' => 'El uso responsable del agua y el cuidado de las áreas verdes ayudan a mantener el balneario limpio y disfrutable para todos.',
                        ],
                    ],
                    'info' => [
                        'cards' => [
                            ['label' => 'Horario', 'value' => 'Lunes a Domingo, 9:00 - 18:00'],
                            ['label' => 'Costo Aproximado', 'value' => '$120 MXN (entrada general)'],
                            ['label' => 'Acceso', 'value' => 'Acceso señalizado desde Tumbalá (tramo asfaltado)'],
                            ['label' => 'Servicios', 'chips' => ['Albercas', 'Sanitarios', 'Comida', 'Área familiar']],
                        ],
                    ],
                    'recomendaciones' => [
                        'items' => [
                            'Llevar traje de baño, toalla y sandalias antiderrapantes',
                            'Evitar ingresar con objetos de vidrio',
                            'Supervisar a niñas y niños en las áreas profundas',
                            'Mantener limpias las áreas comunes',
                        ],
                    ],
                    'ubicacion' => 'Tumbalá, Chiapas. Referencias cercanas: acceso principal y zona de estacionamiento.',
                ],

                'rating' => 4.6,
                'reviews_count' => 98,
                'comentarios' => [
                    ['nombre' => 'María López', 'fecha' => '2026-02-01', 'stars' => 5, 'texto' => 'Muy bonito y limpio, ideal para ir en familia.'],
                    ['nombre' => 'Carlos Méndez', 'fecha' => '2026-01-28', 'stars' => 4, 'texto' => 'Buen lugar, la comida estaba rica. Recomendado.'],
                ],
                'servicios' => [
                    ['icon' => 'bi-cup-hot', 'titulo' => 'Comida y bebidas', 'desc' => 'Opciones locales y snacks dentro del balneario.'],
                    ['icon' => 'bi-parking', 'titulo' => 'Estacionamiento', 'desc' => 'Área de estacionamiento disponible cerca del acceso.'],
                ],
            ],

            'zona-turistica-ayutla' => [
                'titulo' => 'Zona Turística Ayutla',
                'categoria' => 'Turístico',
                'ubicacion' => 'Ocosingo, Chiapas',

                'galeria' => [
                    asset('img/turisticos/ayutla-2.png'),
                    asset('img/turisticos/ayutla-1.png'),
                    asset('img/turisticos/mirador-1.png'),
                ],

                'stats' => [
                    ['icon' => 'bi-clock', 'label' => 'Duración', 'value' => '2-4 horas'],
                    ['icon' => 'bi-compass', 'label' => 'Dificultad', 'value' => 'Baja'],
                    ['icon' => 'bi-camera', 'label' => 'Mejor época', 'value' => 'Diciembre a Mayo'],
                    ['icon' => 'bi-cash-coin', 'label' => 'Costo aprox.', 'value' => '$200 MXN (entrada general)'],
                ],

                'tabs' => [
                    'descripcion' => [
                        'texto' => "Recorrido cultural con actividades y puntos de interés. Ideal para conocer la historia local, artesanías y atractivos cercanos.",
                        'highlight' => [
                            'titulo' => 'Valor cultural y ambiental',
                            'texto' => 'Promueve el respeto a la cultura local y el consumo responsable, apoyando a comunidades y emprendimientos de la región.',
                        ],
                    ],
                    'info' => [
                        'cards' => [
                            ['label' => 'Horario', 'value' => 'Martes a Domingo, 9:00 - 16:30'],
                            ['label' => 'Costo Aproximado', 'value' => '$200 MXN (entrada general)'],
                            ['label' => 'Acceso', 'value' => 'Acceso desde Ocosingo con transporte local disponible'],
                            ['label' => 'Servicios', 'chips' => ['Guías', 'Artesanías', 'Zona de comida', 'Recorridos']],
                        ],
                    ],
                    'recomendaciones' => [
                        'items' => [
                            'Llevar efectivo para artesanías y consumo local',
                            'Usar ropa cómoda y gorra',
                            'Respetar señalización y espacios culturales',
                            'Apoyar guías locales para enriquecer la experiencia',
                        ],
                    ],
                    'ubicacion' => 'Ocosingo, Chiapas. Punto de encuentro recomendado: entrada principal.',
                ],

                'rating' => 4.9,
                'reviews_count' => 312,
                'comentarios' => [
                    ['nombre' => 'Laura Hernández', 'fecha' => '2026-02-12', 'stars' => 5, 'texto' => 'Excelente recorrido, muy completo y bonito.'],
                    ['nombre' => 'Miguel Torres', 'fecha' => '2026-02-09', 'stars' => 5, 'texto' => 'Me encantó la parte cultural y las artesanías.'],
                ],
                'servicios' => [
                    ['icon' => 'bi-bag', 'titulo' => 'Tiendas y artesanías', 'desc' => 'Productos locales y recuerdos tradicionales.'],
                    ['icon' => 'bi-people', 'titulo' => 'Guías expertos', 'desc' => 'Recorridos con explicación cultural e histórica.'],
                ],
            ],
        ];

        abort_unless(isset($detalles[$slug]), 404);

        $d = $detalles[$slug];
        return view('destinos.show', compact('d'));
    }
}