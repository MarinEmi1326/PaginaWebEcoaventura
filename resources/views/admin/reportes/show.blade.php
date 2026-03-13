@extends('layouts.admin')

@section('title', 'Reportes de ' . $destino->nombre)

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.reportes') }}" class="text-decoration-none fw-semibold" style="color: var(--ea-green);">
        ← Volver al listado
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
@endif

<div class="ea-card p-4">

    {{-- Encabezado --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="ea-page-title mb-1">{{ $destino->nombre }}</h1>
            <p class="ea-subtitle mb-0">Ocosingo, Chiapas</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="ea-chip {{ $destino->activo === 'activo' ? 'green' : 'red' }}">
                {{ $destino->activo === 'activo' ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
    </div>

    {{-- Resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="ea-soft-row text-center">
                <div class="ea-summary-number">{{ $total }}</div>
                <div class="ea-summary-label">Reportes totales</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="ea-soft-row text-center">
                <div class="ea-summary-number is-rejected">{{ $pendientes }}</div>
                <div class="ea-summary-label">Pendientes</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="ea-soft-row text-center">
                <div class="ea-summary-number is-approved">{{ $revisados }}</div>
                <div class="ea-summary-label">Revisados</div>
            </div>
        </div>
    </div>

    {{-- Tabla de reportes --}}
    <div class="ea-card p-0 overflow-hidden">
        <div class="p-4 border-bottom"
             style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.18);">
            <h5 class="ea-report-title mb-0">Detalle de Reportes</h5>
        </div>

        <div class="table-responsive">
            <table class="table ea-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th>Reportado por</th>
                        <th>Contenido reportado</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportes as $rep)
                        <tr>
                            <td>{{ $rep->id_reporte }}</td>
                            <td>
                                @if($rep->tipo_objeto === 'comentario')
                                    <span class="badge bg-warning text-dark">Comentario</span>
                                @else
                                    <span class="badge bg-danger">Destino</span>
                                @endif
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $rep->motivo)) }}</td>
                            <td>
                                <div class="fw-semibold small">{{ $rep->nombre_reporter }} {{ $rep->apaterno_reporter }}</div>
                                <div class="text-muted" style="font-size:.75rem;">{{ ucfirst($rep->rol) }}</div>
                            </td>
                            <td>
                                @if($rep->texto_comentario)
                                    <span class="text-muted small fst-italic">
                                        "{{ Str::limit($rep->texto_comentario, 60) }}"
                                    </span>
                                @else
                                    <span class="text-muted small">Destino completo</span>
                                @endif
                                @if($rep->descripcion)
                                    <div class="text-muted" style="font-size:.72rem;">
                                        <i class="bi bi-info-circle me-1"></i>{{ Str::limit($rep->descripcion, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ \Carbon\Carbon::parse($rep->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @php
                                    $chip = match($rep->estado) {
                                        'pendiente' => 'blue',
                                        'en_revision' => 'gray',
                                        'resuelto' => 'green',
                                        'rechazado' => 'red',
                                        default => 'gray'
                                    };
                                @endphp
                                <span class="ea-chip {{ $chip }}">{{ ucfirst($rep->estado) }}</span>
                            </td>
                            <td class="text-center">
                                @if($rep->estado === 'pendiente' || $rep->estado === 'en_revision')
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        {{-- Resolver --}}
                                        <form action="{{ route('admin.reportes.resolver', $rep->id_reporte) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-3 px-2"
                                                    title="Marcar como resuelto">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>

                                        {{-- Rechazar --}}
                                        <form action="{{ route('admin.reportes.rechazar', $rep->id_reporte) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3 px-2"
                                                    title="Rechazar reporte">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>

                                        {{-- Eliminar comentario si aplica --}}
                                        @if($rep->tipo_objeto === 'comentario' && $rep->id_comentario)
                                            <form action="{{ route('admin.reportes.comentario.eliminar', $rep->id_comentario) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Eliminar este comentario y resolver todos sus reportes?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger rounded-3 px-2"
                                                        title="Eliminar comentario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay reportes para este destino.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection