@extends('layouts.app')

@section('content')

<section class="pt-2 pb-5 text-center">
    <div class="container">
        <h1 class="display-5 fw-bold mt-2">Centros Turísticos de Ocosingo</h1>
        <p class="lead text-muted mx-auto" style="max-width: 600px;">
            Catálogo informativo de los principales sitios naturales, arqueológicos
            y comunitarios de la región. Conoce cada lugar antes de visitarlo.
        </p>

        {{-- Buscador --}}
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0"
                        style="border-radius: 50rem 0 0 50rem;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="buscador" class="form-control border-start-0"
                        placeholder="Buscar centros turísticos..."
                        style="border-radius: 0 50rem 50rem 0;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-3 pb-5">
    <div class="container">

        {{-- TABS dinámicos por categoría --}}
        <div class="text-center mb-5">
            <ul class="nav nav-pills justify-content-center p-2 rounded-pill filtro-tabs d-inline-flex" id="destinosTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill"
                        data-bs-target="#tab-todos" type="button">
                        <i class="bi bi-grid-fill me-1"></i> Todos
                    </button>
                </li>
                @foreach ($categorias as $cat)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4" data-bs-toggle="pill"
                            data-bs-target="#tab-cat-{{ $cat->id_categoria }}" type="button">
                            {{ $cat->nombre }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content" id="destinosTabContent">

            {{-- TAB: Todos --}}
            <div class="tab-pane fade show active" id="tab-todos" role="tabpanel">
                <div class="row g-4" id="cards-todos">
                    @forelse ($destinos as $d)
                        <div class="col-md-6 col-lg-4 card-destino">
                            @include('centros._card', ['d' => $d])
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-5">
                            <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                            Aún no hay destinos registrados.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- TAB por cada categoría --}}
            @foreach ($categorias as $cat)
                <div class="tab-pane fade" id="tab-cat-{{ $cat->id_categoria }}" role="tabpanel">
                    <div class="row g-4">
                        @php
                            $destinosCat = $destinos->filter(fn($d) => $d->categorias->contains($cat->nombre));
                        @endphp
                        @forelse ($destinosCat as $d)
                            <div class="col-md-6 col-lg-4">
                                @include('centros._card', ['d' => $d])
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                                No hay destinos en esta categoría aún.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

<script>
    // Buscador en tiempo real (solo en tab "Todos")
    document.getElementById('buscador').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#cards-todos .card-destino').forEach(card => {
            const texto = card.innerText.toLowerCase();
            card.style.display = texto.includes(q) ? '' : 'none';
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const cat = params.get('cat');
        if (cat) {
            const tab = document.querySelector(`[data-bs-target="#tab-cat-${cat}"]`);
            if (tab) {
                const bsTab = new bootstrap.Tab(tab);
                bsTab.show();
            }
        }
    });
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
@endpush