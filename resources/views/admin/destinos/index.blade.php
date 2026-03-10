@extends('layouts.admin')

@section('title', 'Ecoaventura | Administrador de Destinos')

@section('content')

    @if (session('success'))
        <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
    @endif

    {{-- Cards resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="ea-card ea-card-tight text-center">
                <div class="ea-summary-number">{{ $total }}</div>
                <div class="ea-summary-label">Total de destinos</div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="ea-card ea-card-tight text-center">
                <div class="ea-summary-number is-approved">{{ $aprobados }}</div>
                <div class="ea-summary-label">Publicados</div>
            </div>
        </div>
    </div>

    {{-- Encabezado --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h2 class="ea-section-heading">Mis Destinos</h2>
        <a href="{{ route('destinos.create') }}" class="btn ea-btn-green">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Destino
        </a>
    </div>

    {{-- Lista --}}
    <div class="d-grid gap-3">

        @forelse ($destinos as $destino)

            <div class="ea-destination-card">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">

                    <div class="flex-grow-1">
                        <div class="ea-destination-title">{{ $destino->nombre }}</div>
                        <div class="ea-destination-meta">
                            Creado: {{ \Carbon\Carbon::parse($destino->fecha_creacion)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 ms-lg-auto">

                        @if ($destino->activo === 'activo')
                            <span class="ea-status approved">Publicado</span>
                        @else
                            <span class="ea-status pending">Inactivo</span>
                        @endif

                        <div class="ea-actions">
                            <a href="{{ route('destinos.edit', $destino->id_destino) }}"
                               class="ea-action-btn edit" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('destinos.destroy', $destino->id_destino) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este destino?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ea-action-btn delete border-0 bg-transparent p-0" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        @empty

            <div class="ea-card text-center py-5">
                <i class="bi bi-map text-muted" style="font-size: 2.5rem;"></i>
                <div class="mt-3 fw-semibold text-muted">Aún no tienes destinos registrados.</div>
                <a href="{{ route('destinos.create') }}" class="btn ea-btn-green mt-3">
                    <i class="bi bi-plus-lg me-1"></i> Crear mi primer destino
                </a>
            </div>

        @endforelse

    </div>

@endsection