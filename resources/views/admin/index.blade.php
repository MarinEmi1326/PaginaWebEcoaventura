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
    {{-- Cola de aprobación (usuarios pendientes) --}}
    <div class="col-12 col-lg-6">
        <div class="ea-card p-0 overflow-hidden">
            <div class="p-3 border-bottom fw-semibold">📋 Cola de aprobación</div>
            <div class="p-3">
                @if($colaAprobacion->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr><th>Usuario</th><th>Fecha solicitud</th><th></th></tr>
                            </thead>
                            <tbody>
                                @foreach($colaAprobacion as $solicitud)
                                <tr>
                                    <td>
                                        {{ $solicitud->nombre ?? '' }} {{ $solicitud->apellidos ?? '' }}<br>
                                        <span class="small text-muted">{{ $solicitud->correo ?? '' }}</span>
                                    </td>
                                    <td>
                                        {{ isset($solicitud->fecha_solicitud) && $solicitud->fecha_solicitud ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y') : 'Sin fecha' }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.solicitudes.show', $solicitud->id_usuario) }}" class="btn btn-sm btn-outline-success">Revisar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No hay solicitudes pendientes.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Actividad reciente: destinos creados en la última semana --}}
    <div class="col-12 col-lg-6">
        <div class="ea-card p-0 overflow-hidden">
            <div class="p-3 border-bottom fw-semibold">🕒 Destinos recientes (última semana)</div>
            <div class="p-3">
                @if($actividadReciente->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr><th>Destino</th><th>Creado por</th><th>Fecha creación</th></tr>
                            </thead>
                            <tbody>
                                @foreach($actividadReciente as $destino)
                                <tr>
                                    <td>{{ $destino->destino_nombre ?? 'Sin nombre' }}</td>
                                    <td>{{ $destino->creador_nombre ?? '' }} {{ $destino->creador_apellidos ?? '' }}</td>
                                    <td>{{ isset($destino->fecha_creacion) ? \Carbon\Carbon::parse($destino->fecha_creacion)->format('d/m/Y') : 'Sin fecha' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3 mb-0">No hay destinos creados en la última semana.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection