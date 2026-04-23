@extends('layouts.admin')

@section('title', 'Detalle del Destino')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.destino') }}"
       class="text-decoration-none fw-semibold"
       style="color: var(--ea-green);">
        ← Volver al listado
    </a>
    <h1 class="ea-page-title mt-3 mb-1">{{ $destino->nombre }}</h1>
    <p class="ea-subtitle">Información general del destino y sus reportes.</p>
</div>



{{-- Info general --}}
<div class="ea-card p-0 overflow-hidden mb-4">
    <div class="p-4 border-bottom"
         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
        <div class="fw-semibold">Información del Destino</div>
    </div>
    <div class="p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="small text-muted mb-1">Nombre</div>
                <div class="fw-semibold">{{ $destino->nombre }}</div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Creado por</div>
                <div class="fw-semibold">{{ $destino->creador }}</div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Fecha de creación</div>
                <div>{{ \Carbon\Carbon::parse($destino->fecha_creacion)->format('d/m/Y') }}</div>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Estado</div>
                <span class="ea-chip {{ $destino->activo === 'activo' ? 'green' : 'red' }}">
                    {{ $destino->activo === 'activo' ? 'Activo' : 'Suspendido' }}
                </span>
            </div>
            <div class="col-md-6">
                <div class="small text-muted mb-1">Categorías</div>
                <div>{{ $destino->categorias ?? '—' }}</div>
            </div>
            @if ($destino->telefono)
                <div class="col-md-6">
                    <div class="small text-muted mb-1">Teléfono</div>
                    <div>{{ $destino->telefono }}</div>
                </div>
            @endif
            @if ($destino->descripcion)
                <div class="col-12">
                    <div class="small text-muted mb-1">Descripción</div>
                    <div class="small">{{ $destino->descripcion }}</div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Resumen reportes --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ea-card text-center p-4">
            <div class="fw-bold" style="font-size:2rem;">{{ $totalReportes }}</div>
            <div class="small text-muted">Reportes totales</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ea-card text-center p-4">
            <div class="fw-bold text-danger" style="font-size:2rem;">{{ $reportesPendientes }}</div>
            <div class="small text-muted">Pendientes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ea-card text-center p-4">
            <div class="fw-bold text-success" style="font-size:2rem;">{{ $reportesResueltos }}</div>
            <div class="small text-muted">Resueltos / Rechazados</div>
        </div>
    </div>
</div>

{{-- Tabla reportes --}}
<div class="ea-card p-0 overflow-hidden">
    <div class="p-4 border-bottom"
         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
        <div class="fw-semibold">Reportes del destino</div>
    </div>

    <div class="table-responsive">
        <table class="table ea-table align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Motivo</th>
                    <th>Descripción</th>
                    <th>Reportado por</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportes as $i => $rep)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $rep->motivo)) }}</td>
                        <td class="small text-muted">{{ $rep->descripcion ?? '—' }}</td>
                        <td>{{ $rep->reportado_por_nombre }}</td>
                        <td class="small">
                            {{ \Carbon\Carbon::parse($rep->fecha)->format('d/m/Y') }}
                        </td>
                        <td>
                            @php
                                $chip = match($rep->estado) {
                                    'pendiente'   => 'blue',
                                    'resuelto'    => 'green',
                                    'rechazado'   => 'red',
                                    'en_revision' => 'gray',
                                    default       => 'gray'
                                };
                            @endphp
                            <span class="ea-chip {{ $chip }}">{{ ucfirst($rep->estado) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Este destino no tiene reportes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection