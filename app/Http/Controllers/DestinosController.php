<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DestinosController extends Controller
{
    private function getDestinos(): array
    {
        return [
            1 => [
                'id' => 1,
                'nombre' => 'Zona Arqueológica de Toniná',
                'tipo' => 'Turistico',
                'icono' => 'bi-building',
                'ubicacion' => 'Ocosingo, Chiapas',
                'descripcion' => 'Toniná fue una poderosa ciudad-estado maya. Su acrópolis de siete plataformas alcanza 75 metros de altura.',
                'descripcion2' => 'El museo exhibe el mural de estuco de los Cuatro Soles.',
                'valor' => 'Centro político que rivalizó con Palenque.',
                'duracion' => '3-4 horas',
                'dificultad' => 'Moderada',
                'epoca' => 'Nov - Abr',
                'costo' => '$85 MXN',
                'horario' => 'Martes a domingo, 9:00 AM - 5:00 PM',
                'imagen' => 'img/turisticos/tonina-1.png',
                'imagen2' => 'img/turisticos/tonina-1.png',
                'imagen3' => 'img/turisticos/tonina-1.png',
                'tags' => ['Pirámide 7 plataformas', 'Museo de sitio', 'Escultura maya'],
                'como_llegar' => 'Desde Ocosingo: 14 km. Taxi colectivo desde el mercado.',
                'servicios' => ['Museo incluido', 'Guías locales', 'Estacionamiento', 'Sanitarios'],
                'llevar' => ['Agua 2L', 'Protector solar', 'Calzado cómodo', 'Repelente'],
                'prohibido' => ['No tocar esculturas', 'No extraer plantas', 'No fumar', 'Sin mascotas'],
                'consejo' => 'Llega antes de las 10 AM. Guía local cuesta $200-300 MXN.',
                'mapa_q' => 'Zona+Arqueologica+Tonina,Ocosingo,Chiapas,Mexico',
                'direccion' => 'Carretera Ocosingo-Altamirano Km 14',
                'categoria' => 'turisticos',
                'rating' => 4.5,
                'resenas' => 2,
            ],
            2 => [
                'id' => 2,
                'nombre' => 'Ayutla',
                'tipo' => 'Turistico',
                'icono' => 'bi-building',
                'ubicacion' => 'Ocosingo, Chiapas',
                'descripcion' => 'Famoso por sus murales policromos, considerados los más importantes del mundo maya.',
                'descripcion2' => 'Sus estelas y templos narran la historia política de la región.',
                'valor' => 'Patrimonio cultural invaluable de la civilización maya.',
                'duracion' => '3-4 horas',
                'dificultad' => 'Moderada',
                'epoca' => 'Nov - Abr',
                'costo' => '$75 MXN',
                'horario' => 'Martes a domingo, 9:00 AM - 5:00 PM',
                'imagen'  => 'img/turisticos/bonampak-1.png',
                'imagen2' => 'img/turisticos/bonampak-1.png',
                'imagen3' => 'img/turisticos/bonampak-1.png',
                'tags' => ['Murales policromos', 'Templo Pinturas', 'Estelas'],
                'como_llegar' => 'Desde Ocosingo en transporte local.',
                'servicios' => ['Guías locales', 'Estacionamiento', 'Sanitarios'],
                'llevar' => ['Agua 2L', 'Protector solar', 'Calzado cómodo', 'Repelente'],
                'prohibido' => ['No tocar murales', 'No extraer plantas', 'No fumar', 'Sin mascotas'],
                'consejo' => 'Visita temprano para aprovechar la luz natural en los murales.',
                'mapa_q' => 'Ayutla,Ocosingo,Chiapas,Mexico',
                'direccion' => 'Ocosingo, Chiapas',
                'categoria' => 'turisticos',
                'rating' => 4.3,
                'resenas' => 1,
            ],
            3 => [
                'id' => 3,
                'nombre' => 'Mirador',
                'tipo' => 'Turistico',
                'icono' => 'bi-building',
                'ubicacion' => 'Ocosingo, Chiapas',
                'descripcion' => 'Ciudad maya a orillas del río Usumacinta, accesible solo por lancha.',
                'descripcion2' => 'Rodeada de selva y fauna silvestre única.',
                'valor' => 'Uno de los sitios mayas más remotos y mejor conservados.',
                'duracion' => '4-5 horas',
                'dificultad' => 'Alta',
                'epoca' => 'Nov - Mar',
                'costo' => '$90 MXN',
                'horario' => 'Martes a domingo, 9:00 AM - 4:00 PM',
                'imagen'  => 'img/turisticos/yaxchilan-1.png',
                'imagen2' => 'img/turisticos/yaxchilan-1.png',
                'imagen3' => 'img/turisticos/yaxchilan-1.png',
                'tags' => ['Río Usumacinta', 'Selva', 'Fauna'],
                'como_llegar' => 'Acceso por lancha desde la ribera del Usumacinta.',
                'servicios' => ['Guías locales', 'Transporte fluvial'],
                'llevar' => ['Agua 3L', 'Repelente', 'Ropa ligera', 'Calzado resistente'],
                'prohibido' => ['No disturbar fauna', 'No extraer plantas', 'No fumar'],
                'consejo' => 'Contrata guía local obligatorio. Lleva efectivo.',
                'mapa_q' => 'Yaxchilan,Ocosingo,Chiapas,Mexico',
                'direccion' => 'Ribera del Usumacinta, Chiapas',
                'categoria' => 'turisticos',
                'rating' => 4.7,
                'resenas' => 3,
            ],
            4 => [
                'id' => 4,
                'nombre' => 'Laguna Miramar',
                'tipo' => 'Ecoturistico',
                'icono' => 'bi-tree',
                'ubicacion' => 'Ocosingo, Chiapas',
                'descripcion' => 'Laguna prístina en la Selva Lacandona, la más grande de México en zona selvática.',
                'descripcion2' => 'Acceso comunitario con turismo responsable.',
                'valor' => 'Ecosistema único con biodiversidad excepcional.',
                'duracion' => '2 días',
                'dificultad' => 'Alta',
                'epoca' => 'Nov - Mar',
                'costo' => '$150 MXN',
                'horario' => 'Con previa reservación',
                'imagen' => 'img/ecoturisticos/miramar-1.png',
                'imagen2' => 'img/ecoturisticos/miramar-1.png',
                'imagen3' => 'img/ecoturisticos/miramar-1.png',
                'tags' => ['Selva Lacandona', 'Kayak', 'Comunitario'],
                'como_llegar' => 'Desde Ocosingo en transporte 4x4 comunitario.',
                'servicios' => ['Kayak', 'Guías locales', 'Campamento', 'Alimentación'],
                'llevar' => ['Agua 3L', 'Repelente', 'Ropa para lluvia', 'Linterna'],
                'prohibido' => ['No contaminar laguna', 'No pesca', 'Sin drones'],
                'consejo' => 'Reserva con al menos una semana de anticipación.',
                'mapa_q' => 'Laguna+Miramar,Ocosingo,Chiapas,Mexico',
                'direccion' => 'Selva Lacandona, Ocosingo, Chiapas',
                'categoria' => 'ecoturisticos',
                'rating' => 4.9,
                'resenas' => 5,
            ],
            5 => [
                'id' => 5,
                'nombre' => 'Don Dimas',
                'tipo' => 'Balneario',
                'icono' => 'bi-water',
                'ubicacion' => 'Ocosingo, Chiapas',
                'descripcion' => 'Impresionantes cascadas de aguas turquesas rodeadas de vegetación tropical.',
                'descripcion2' => 'Ideal para nadar, descansar y disfrutar de la naturaleza.',
                'valor' => 'Balneario natural con aguas cristalinas únicas en la región.',
                'duracion' => '1 día',
                'dificultad' => 'Fácil',
                'epoca' => 'Todo el año',
                'costo' => '$50 MXN',
                'horario' => 'Todos los días, 8:00 AM - 6:00 PM',
                'imagen'  => 'img/balnearios/agua-azul-1.png',
                'imagen2' => 'img/balnearios/agua-azul-1.png',
                'imagen3' => 'img/balnearios/agua-azul-1.png',
                'tags' => ['Aguas turquesas', 'Natación', 'Naturaleza'],
                'como_llegar' => 'Carretera desde Ocosingo, bien señalizado.',
                'servicios' => ['Área de nado', 'Sanitarios', 'Área de picnic', 'Estacionamiento'],
                'llevar' => ['Traje de baño', 'Toalla', 'Protector solar', 'Agua'],
                'prohibido' => ['No jabón en el agua', 'No vidrio', 'No música alta'],
                'consejo' => 'Visita entre semana para evitar aglomeraciones.',
                'mapa_q' => 'Don+Dimas,Ocosingo,Chiapas,Mexico',
                'direccion' => 'Ocosingo, Chiapas',
                'categoria' => 'balnearios',
                'rating' => 4.4,
                'resenas' => 4,
            ],
            6 => [
                'id' => 6,
                'nombre' => 'Comunidad Lacandona Nahá',
                'tipo' => 'Ecoturistico',
                'icono' => 'bi-tree',
                'ubicacion' => 'Ocosingo, Chiapas',
                'descripcion' => 'Comunidad lacandona en la selva con turismo comunitario, laguna, aves y cultura ancestral viva.',
                'descripcion2' => 'Una experiencia única de convivencia con la cultura lacandona.',
                'valor' => 'Preservación de cultura indígena y ecosistemas selváticos.',
                'duracion' => '1-2 días',
                'dificultad' => 'Moderada',
                'epoca' => 'Nov - Mar',
                'costo' => '$120 MXN',
                'horario' => 'Con previa reservación',
                'imagen' => 'img/ecoturisticos/naha-1.png',
                'imagen2' => 'img/ecoturisticos/naha-1.png',
                'imagen3' => 'img/ecoturisticos/naha-1.png',
                'tags' => ['Lacandones', 'Aves', 'Cultura viva'],
                'como_llegar' => 'Desde Ocosingo en transporte comunitario.',
                'servicios' => ['Hospedaje comunitario', 'Guías locales', 'Alimentación típica'],
                'llevar' => ['Ropa cómoda', 'Repelente', 'Agua', 'Efectivo'],
                'prohibido' => ['Respetar costumbres', 'No fotografiar sin permiso', 'No contaminar'],
                'consejo' => 'Respeta las tradiciones de la comunidad. Aprende algunas palabras en lacandón.',
                'mapa_q' => 'Naha,Ocosingo,Chiapas,Mexico',
                'direccion' => 'Comunidad Nahá, Ocosingo, Chiapas',
                'categoria' => 'ecoturisticos',
                'rating' => 4.6,
                'resenas' => 3,
            ],
        ];
    }

    public function index()
    {
        $destinos = $this->getDestinos();
        return view('centros.index', compact('destinos'));
    }

    public function show($id)
    {
        $destinos = $this->getDestinos();
        if (!isset($destinos[$id])) abort(404);

        $destino = $destinos[$id];
        $otrosDestinos = array_slice(
            array_filter($destinos, fn($d) => $d['id'] !== (int)$id),
            0,
            3
        );
        $googleMapsKey = env('GOOGLE_MAPS_KEY');

        return view('centros.show', compact('destino', 'otrosDestinos', 'googleMapsKey'));
    }

    public function tipo($tipo)
    {
        $destinos = $this->getDestinos();
        $mapa = [
            'turisticos'    => 'turisticos',
            'ecoturisticos' => 'ecoturisticos',
            'balnearios'    => 'balnearios',
        ];
        $categoriaActiva = $mapa[$tipo] ?? 'turisticos';
        return view('centros.index', compact('destinos', 'categoriaActiva'));
    }
}
