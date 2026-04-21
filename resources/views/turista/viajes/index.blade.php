@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="mb-4">
            <h1 class="fw-bold">Mis Viajes</h1>
            <p class="text-muted">Historial de paquetes turísticos adquiridos.</p>
        </div>

        <div class="row g-4">
            @forelse($viajes as $viaje)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <span class="ea-chip green">Confirmado</span>
                            </div>
                            <h5 class="fw-bold mb-2">{{ $viaje->paquete_nombre }}</h5>
                            <p class="text-muted small mb-3">{{ $viaje->destino_nombre }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ \Carbon\Carbon::parse($viaje->fecha)->format('d/m/Y') }}
                                </span>
                                <span class="fw-bold text-success fs-5">${{ number_format($viaje->monto, 2) }} MXN</span>
                            </div>
                            <a href="{{ route('turista.viajes.show', $viaje->id_pago) }}"
                                class="btn btn-outline-success rounded-3 w-100">
                                <i class="bi bi-eye me-1"></i> Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-suitcase fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted">No tienes viajes registrados.</p>
                        <a href="{{ route('destinos.index') }}" class="btn btn-success rounded-3">
                            Explorar destinos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
