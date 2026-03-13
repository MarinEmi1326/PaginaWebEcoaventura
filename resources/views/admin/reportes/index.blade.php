@extends('layouts.admin')

@section('title', 'Reportes del Sistema')

@section('content')

<div class="mb-4">
    <h1 class="ea-page-title mb-1">Reportes del Sistema</h1>
    <p class="ea-subtitle mb-0">Resumen general de todos los reportes recibidos.</p>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
@endif

{{-- Cards resumen --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="ea-card p-4 text-center h-100">
            <div class="ea-report-icon mx-auto mb-3" style="background: rgba(228,87,46,.10); color:#e4572e;">
                <i class="bi bi-flag"></i>
            </div>
            <div class="ea-summary-number">{{ $total }}</div>
            <div class="ea-summary-label">Reportes totales</div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="ea-card p-4 text-center h-100">
            <div class="ea-report-icon mx-auto mb-3" style="background: rgba(30,136,229,.10); color:#1e88e5;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="ea-summary-number is-pending">{{ $pendientes }}</div>
            <div class="ea-summary-label">Pendientes de revisión</div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="ea-card p-4 text-center h-100">
            <div class="ea-report-icon mx-auto mb-3" style="background: rgba(63,125,59,.12); color:#3f7d3b;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="ea-summary-number is-approved">{{ $revisados }}</div>
            <div class="ea-summary-label">Revisados</div>
        </div>
    </div>
</div>

{{-- Bloques centrales --}}
<div class="row g-3 mb-4">
    {{-- Por destino --}}
    <div class="col-12 col-xl-6">
        <div class="ea-card p-3 p-md-4 h-100">
            <h5 class="ea-report-title mb-3">Por destino</h5>
            <div class="d-grid gap-2" style="max-height: 280px; overflow-y: auto; padding-right: 6px;">
                @forelse($porDestino as $d)
                    <a href="{{ route('admin.reportes.showDestino', $d->id_destino) }}"
                       class="ea-soft-row d-flex justify-content-between align-items-center text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold">{{ $d->nombre }}</div>
                            <div class="small" style="color: var(--ea-muted);">
                                {{ $d->pendientes }} pendiente{{ $d->pendientes != 1 ? 's' : '' }}
                            </div>
                        </div>
                        <span class="ea-chip {{ $d->pendientes > 0 ? 'red' : 'gray' }}">{{ $d->total }}</span>
                    </a>
                @empty
                    <p class="text-muted small text-center py-3">No hay reportes por destino aún.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Por usuario --}}
    <div class="col-12 col-xl-6">
        <div class="ea-card p-3 p-md-4 h-100">
            <h5 class="ea-report-title mb-3">Por usuario</h5>
            <div class="d-grid gap-2" style="max-height: 280px; overflow-y: auto; padding-right: 6px;">
                @forelse($porUsuario as $u)
                    <div class="ea-soft-row d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="ea-avatar">{{ strtoupper(substr($u->nombre ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div class="fw-semibold">{{ $u->nombre }} {{ $u->apaterno }}</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    {{ match($u->rol) {
                                        'turista' => 'Turista',
                                        'admin_destinos' => 'Admin. de Destinos',
                                        'gestor_rutas' => 'Gestor de Rutas',
                                        default => ucfirst($u->rol)
                                    } }}
                                </div>
                            </div>
                        </div>
                        <span class="ea-chip gray">{{ $u->total }}</span>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">No hay datos aún.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection