@extends('layouts.app')

@section('content')
    {{-- HERO --}}
    <section class="position-relative d-flex align-items-center text-white p-0"
        style="background: url('{{ asset('img/tonina.jpeg') }}') center center / cover no-repeat; min-height: 100vh;">

        {{-- Overlay --}}
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background: linear-gradient(90deg, rgba(6,78,59,0.75) 0%, rgba(6,95,70,0.55) 50%, rgba(6,95,70,0.35) 100%);">
        </div>

        <div class="container-fluid position-relative px-5">
            <div class="col-lg-7">

                <span class="badge rounded-pill bg-light bg-opacity-25 text-white px-4 py-2 mb-4">
                    Turismo sostenible y responsable
                </span>

                <h1 class="display-3 fw-bold lh-1">
                    Descubre la magia de la naturaleza
                </h1>

                <p class="lead mt-4 text-white-50">
                    Vive experiencias únicas de ecoturismo. Explora destinos extraordinarios,
                    conecta con la naturaleza y crea recuerdos inolvidables.
                </p>

            </div>
        </div>
    </section>

    {{-- DESTINOS DESTACADOS --}}
    <section class="py-5" style="background:#F7F6EF;">
        <div class="container text-center">

            <p class="text-uppercase text-success small fw-semibold">Destinos destacados</p>
            <h2 class="fw-bold mb-5">Explora lugares extraordinarios</h2>

            <div class="row g-4">

                @foreach ([['img' => 'ecoturisticos/miramar-1.png', 'tipo' => 'Ecoturístico', 'titulo' => 'Miramar', 'ubicacion' => 'Sierra Azul'], ['img' => 'balnearios/encanto-1.png', 'tipo' => 'Balneario', 'titulo' => 'Playa Cristalina', 'ubicacion' => 'Costa Azul'], ['img' => 'turisticos/mirador-1.png', 'tipo' => 'Turístico', 'titulo' => 'Ruinas Ancestrales', 'ubicacion' => 'Valle Sagrado']] as $destino)
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm overflow-hidden h-100 rounded-4">

                            <div class="overflow-hidden">
                                <img src="{{ asset('img/' . $destino['img']) }}" class="w-100"
                                    style="height:420px; object-fit:cover; transition:transform .6s;">
                            </div>

                            <div class="card-body text-start">
                                <span class="badge bg-success mb-2">{{ $destino['tipo'] }}</span>
                                <h5 class="fw-bold">{{ $destino['titulo'] }}</h5>
                                <p class="text-muted small">{{ $destino['ubicacion'] }}</p>
                                <a href="#" class="btn btn-outline-success btn-sm rounded-pill">
                                    Ver más →
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </section>

    {{-- SERVICIOS --}}
    <section class="py-5">
        <div class="container text-center">

            <p class="text-uppercase text-success small fw-semibold">Nuestros servicios</p>
            <h2 class="fw-bold mb-5">Todo para tu aventura</h2>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                        <img src="{{ asset('img/Hoteles/ex-hacienda-1.png') }}" class="w-100"
                            style="height:350px; object-fit:cover;">
                        <div class="card-body text-start">
                            <h5 class="fw-bold">Hospedaje Ecológico</h5>
                            <p class="text-muted">
                                Eco-lodges y hoteles boutique en armonía con la naturaleza.
                            </p>
                            <a href="#" class="btn btn-success btn-sm rounded-pill">
                                Explorar →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                        <img src="{{ asset('img/Restaurantes/espresso-1.png') }}" class="w-100"
                            style="height:350px; object-fit:cover;">
                        <div class="card-body text-start">
                            <h5 class="fw-bold">Gastronomía Local</h5>
                            <p class="text-muted">
                                Restaurantes con cocina tradicional y gourmet.
                            </p>
                            <a href="#" class="btn btn-success btn-sm rounded-pill">
                                Explorar →
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="py-5 text-white text-center" style="background: linear-gradient(to right, #064e3b, #065f46);">

        <div class="container">
            <h2 class="fw-bold display-6">
                Comienza tu próxima ecoaventura hoy
            </h2>

            <p class="mt-3">
                Regístrate gratis y accede a ofertas exclusivas.
            </p>

            <a href="{{ route('register') }}" class="btn btn-light text-success fw-semibold mt-3 px-4 py-2 rounded-pill">
                Crear cuenta gratis →
            </a>
        </div>
    </section>

    @include('layouts.footer')
@endsection
