@extends('layouts.app')

@section('content')
    {{-- HERO --}}
    <section class="py-5 bg-light text-center">
        <div class="container">
            <span class="text-success fw-bold text-uppercase small">Explora Ocosingo</span>
            <h1 class="display-5 fw-bold mt-2">Rutas Turísticas</h1>
            <p class="lead text-muted">
                Descubre los recorridos más fascinantes por la región de Ocosingo.
                Planifica tu aventura con rutas detalladas y guías paso a paso.
            </p>

            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 50rem 0 0 50rem;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="buscador" class="form-control border-start-0"
                            placeholder="Buscar rutas..." style="border-radius: 0 50rem 50rem 0;" onkeyup="filtrarRutas()">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FILTROS --}}
   <section class="py-3 border-bottom bg-white">
        <div class="container">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <i class="bi bi-funnel text-muted me-1"></i>
                <button class="btn btn-success btn-sm rounded-pill filtro-dificultad active-filtro"
                    onclick="filtrarDificultad('todas', this)">Todas</button>
                <button class="btn btn-outline-success btn-sm rounded-pill filtro-dificultad"
                    onclick="filtrarDificultad('baja', this)">Fácil</button>
                <button class="btn btn-outline-success btn-sm rounded-pill filtro-dificultad"
                    onclick="filtrarDificultad('media', this)">Moderada</button>
                <button class="btn btn-outline-success btn-sm rounded-pill filtro-dificultad"
                    onclick="filtrarDificultad('alta', this)">Difícil</button>
            </div>
        </div>
    </section>

    {{-- LISTA DE RUTAS --}}
    <section class="py-5">
        <div class="container">

            <p class="text-muted mb-4" id="contador-rutas">
                {{ $rutas->count() }} ruta(s) encontrada(s)
            </p>

            <div class="row g-4" id="contenedor-rutas">

                @forelse ($rutas as $ruta)
                    <div class="col-md-6 col-lg-4 card-ruta" data-dificultad="{{ $ruta->dificultad }}"
                        data-nombre="{{ strtolower($ruta->nombre) }}"
                        data-descripcion="{{ strtolower($ruta->descripcion) }}">

                        <a href="{{ route('rutas.show', $ruta->id_ruta) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                                {{-- Imagen --}}
                                <div class="rounded-top-4 position-relative overflow-hidden" style="height: 200px;">
                                    @if ($ruta->imagen)
                                        <img src="{{ Storage::url($ruta->imagen) }}" class="w-100 h-100"
                                            style="object-fit: cover;" alt="{{ $ruta->nombre }}">
                                    @else
                                        <div
                                            class="w-100 h-100 bg-success bg-opacity-10
                                                d-flex align-items-center justify-content-center">
                                            <i class="bi bi-map display-4 text-success opacity-25"></i>
                                        </div>
                                    @endif

                                    {{-- Badge dificultad --}}
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if ($ruta->dificultad === 'baja')
                                            <span class="badge badge-facil">Fácil</span>
                                        @elseif ($ruta->dificultad === 'media')
                                            <span class="badge badge-moderada">Moderada</span>
                                        @else
                                            <span class="badge badge-alta">Difícil</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-dark mb-1">{{ $ruta->nombre }}</h5>

                                    {{-- Creador (ahora viene del controlador) --}}
                                    @if ($ruta->creador_nombre)
                                        <div class="small text-muted mb-2">
                                            <i class="bi bi-person-circle me-1"></i>
                                            Creado por {{ $ruta->creador_nombre }} {{ $ruta->creador_apellidos }}
                                        </div>
                                    @endif

                                    <p class="card-text text-muted small">
                                        {{ Str::limit($ruta->descripcion, 100) }}
                                    </p>

                                    {{-- Datos claros --}}
                                    <div class="d-flex flex-wrap gap-2 small mb-3">

                                        @if ($ruta->duracion_estimada)
                                            <span class="d-flex align-items-center gap-1 px-2 py-1 rounded-3"
                                                style="background:#f0f7f4; color:#1F6B4B;">
                                                <i class="bi bi-clock"></i>
                                                Duración: {{ $ruta->duracion_estimada }}
                                            </span>
                                        @endif

                                        @if ($ruta->distancia_km)
                                            <span class="d-flex align-items-center gap-1 px-2 py-1 rounded-3"
                                                style="background:#f0f7f4; color:#1F6B4B;">
                                                <i class="bi bi-signpost-2"></i>
                                                {{ $ruta->distancia_km }} km de recorrido
                                            </span>
                                        @endif

                                    </div>

                                    {{-- Fechas de operación --}}
                                    @if ($ruta->fecha_inicio_operacion && $ruta->fecha_fin_operacion)
                                        <div class="small mb-3 px-2 py-1 rounded-3"
                                            style="background:#fff8e1; color:#795548;">
                                            <i class="bi bi-calendar-range me-1"></i>
                                            Disponible del
                                            {{ \Carbon\Carbon::parse($ruta->fecha_inicio_operacion)->format('d/m/Y') }}
                                            al
                                            {{ \Carbon\Carbon::parse($ruta->fecha_fin_operacion)->format('d/m/Y') }}
                                        </div>
                                    @endif

                                    {{-- Pie --}}
                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                        <span class="small text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $ruta->total_paradas }} parada(s)
                                        </span>
                                        <span class="text-success fw-semibold small">
                                            Ver ruta <i class="bi bi-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>

                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-signpost-2 text-muted" style="font-size: 3rem;"></i>
                        <div class="mt-3 fw-semibold text-muted">Aún no hay rutas disponibles.</div>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    {{-- TEMPORADAS --}}
    <section class="py-5 bg-light">
        <div class="container text-center">

            <h2 class="fw-bold mb-2">¿Cuándo visitar Ocosingo?</h2>
            <p class="text-muted mb-5">Cada temporada ofrece una experiencia diferente.</p>

            <div class="row g-4 justify-content-center">

                <div class="col-md-5">
                    <div class="card border-success shadow-sm rounded-4 h-100">
                        <div class="card-body text-start p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="bi bi-sun fs-3 text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Temporada Seca</h5>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <small class="text-muted">Noviembre – Abril</small>
                                        <span class="badge badge-eco" style="font-size: 0.7rem;">Recomendada</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">
                                Ideal para la mayoría de actividades. Cielos despejados,
                                caminos accesibles, cascadas con colores turquesa intenso.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-eco">Senderismo</span>
                                <span class="badge badge-eco">Cascadas</span>
                                <span class="badge badge-eco">Arqueología</span>
                                <span class="badge badge-eco">Laguna Miramar</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body text-start p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="bi bi-cloud-rain fs-3 text-secondary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Temporada de Lluvias</h5>
                                    <small class="text-muted">Mayo – Octubre</small>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">
                                La selva en su máximo esplendor verde. Menos visitantes,
                                precios accesibles. Algunas rutas pueden estar más difíciles de transitar.
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-eco">Observación de aves</span>
                                <span class="badge badge-eco">Cultura</span>
                                <span class="badge badge-eco">Zonas arqueológicas</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        function filtrarDificultad(dificultad, boton) {
            document.querySelectorAll('.filtro-dificultad').forEach(b => {
                b.classList.remove('btn-success');
                b.classList.add('btn-outline-success');
            });
            boton.classList.remove('btn-outline-success');
            boton.classList.add('btn-success');

            const cards = document.querySelectorAll('.card-ruta');
            let visibles = 0;

            cards.forEach(card => {
                const coincide = dificultad === 'todas' || card.dataset.dificultad === dificultad;
                card.style.display = coincide ? '' : 'none';
                if (coincide) visibles++;
            });

            document.getElementById('contador-rutas').textContent = visibles + ' ruta(s) encontrada(s)';
        }

        function filtrarRutas() {
            const texto = document.getElementById('buscador').value.toLowerCase();
            const cards = document.querySelectorAll('.card-ruta');
            let visibles = 0;

            cards.forEach(card => {
                const coincide = card.dataset.nombre.includes(texto) ||
                    card.dataset.descripcion.includes(texto);
                card.style.display = coincide ? '' : 'none';
                if (coincide) visibles++;
            });

            document.getElementById('contador-rutas').textContent = visibles + ' ruta(s) encontrada(s)';
        }
    </script>
@endsection
