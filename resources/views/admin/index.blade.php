@extends('layouts.admin')

@section('title', 'Dashboard - Admin General')

@section('content')
<div class="mb-4">
    <h1 class="ea-page-title mb-1">Dashboard</h1>
    <p class="ea-subtitle mb-0">Bienvenido al panel de administración general.</p>
</div>

{{-- Tarjetas de resumen --}}
<div class="row g-3 mb-5">
    <div class="col-12 col-md-3">
        <div class="ea-card p-3 text-center">
            <div class="ea-summary-number">{{ $totalUsuarios ?? 0 }}</div>
            <div class="ea-summary-label">Total usuarios</div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="ea-card p-3 text-center">
            <div class="ea-summary-number is-pending">{{ $pendientes ?? 0 }}</div>
            <div class="ea-summary-label">Pendientes</div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="ea-card p-3 text-center">
            <div class="ea-summary-number is-approved">{{ $publicados ?? 0 }}</div>
            <div class="ea-summary-label">Aprobados</div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="ea-card p-3 text-center">
            <div class="ea-summary-number is-rejected">{{ $rechazados ?? 0 }}</div>
            <div class="ea-summary-label">Rechazados</div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Cola de aprobación --}}
    <div class="col-12 col-lg-6">
        <div class="ea-card p-0 overflow-hidden h-100">
            <div class="p-4 border-bottom fw-semibold"
                 style="border-color: var(--ea-line) !important;">
                Cola de aprobación
            </div>

            <div class="p-4">
                @if(isset($colaAprobacion) && $colaAprobacion->count() > 0)
                    <div class="d-grid gap-3">
                        @foreach($colaAprobacion as $solicitud)
                            <div class="rounded-4 px-3 py-3 d-flex justify-content-between align-items-center flex-wrap gap-3"
                                 style="background: rgba(245,248,245,0.95); border: 1px solid rgba(15,42,36,.05);">

                                <div>
                                    <div class="fw-semibold" style="font-size: 1.1rem;">
                                        {{ trim(($solicitud->nombre ?? '') . ' ' . ($solicitud->apellidos ?? '')) ?: 'Usuario sin nombre' }}
                                    </div>

                                    <div class="small" style="color: var(--ea-muted);">
                                        {{ ucfirst(str_replace('_', ' ', $solicitud->rol ?? 'usuario')) }}
                                        ·
                                        {{ $solicitud->correo ?? 'Sin correo' }}
                                    </div>

                                    <div class="small" style="color: var(--ea-muted);">
                                        Solicitud:
                                        {{ !empty($solicitud->fecha_solicitud) ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y') : 'Sin fecha' }}
                                    </div>
                                </div>

                                <div>
                                    <a href="{{ route('admin.solicitudes.show', $solicitud->id_usuario) }}"
                                       class="btn px-4 py-2 fw-semibold rounded-4"
                                       style="background: #0f5a3a; color: white; border: none;">
                                        Revisar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">No hay solicitudes pendientes.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Destinos nuevos de las últimas 2 semanas --}}
    <div class="col-12 col-lg-6">
        <div class="ea-card p-0 overflow-hidden h-100">
            <div class="p-4 border-bottom fw-semibold"
                 style="border-color: var(--ea-line) !important;">
                Destinos nuevos (últimas 2 semanas)
            </div>

            <div class="p-4">
                @if(isset($actividadReciente) && $actividadReciente->count() > 0)
                    <div class="d-grid gap-3">
                        @foreach($actividadReciente as $destino)
                            <div class="rounded-4 px-3 py-3 d-flex justify-content-between align-items-center flex-wrap gap-3"
                                 style="background: rgba(245,248,245,0.95); border: 1px solid rgba(15,42,36,.05);">

                                <div>
                                    <div class="fw-semibold" style="font-size: 1.1rem;">
                                        {{ $destino->destino_nombre ?? 'Sin nombre' }}
                                    </div>

                                    <div class="small" style="color: var(--ea-muted);">
                                        {{ $destino->categoria ?? 'Sin categoría' }}
                                    </div>

                                    <div class="small" style="color: var(--ea-muted);">
                                        Creado por:
                                        {{ trim(($destino->creador_nombre ?? '') . ' ' . ($destino->creador_apellidos ?? '')) ?: 'Sin autor' }}
                                    </div>

                                    <div class="small" style="color: var(--ea-muted);">
                                        Fecha:
                                        {{ !empty($destino->fecha_creacion) ? \Carbon\Carbon::parse($destino->fecha_creacion)->format('d/m/Y') : 'Sin fecha' }}
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <span class="ea-chip blue">
                                        Nuevo
                                    </span>

                                    <a href="#"
                                       class="text-decoration-none"
                                       style="color: var(--ea-text); font-size: 1.1rem;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">No hay destinos nuevos en las últimas 2 semanas.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection