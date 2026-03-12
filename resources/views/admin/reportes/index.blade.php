@extends('layouts.admin')

@section('title', 'Reportes del Sistema')

@section('content')

<div class="mb-4">
    <h1 class="ea-page-title mb-1">Reportes del Sistema</h1>
    <p class="ea-subtitle mb-0">Resumen general de todos los reportes recibidos.</p>
</div>

{{-- Cards resumen --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-4">
        <div class="ea-card p-4 text-center h-100">
            <div class="ea-report-icon mx-auto mb-3" style="background: rgba(228,87,46,.10); color:#e4572e;">
                <i class="bi bi-flag"></i>
            </div>
            <div class="ea-summary-number">92</div>
            <div class="ea-summary-label">Reportes totales</div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="ea-card p-4 text-center h-100">
            <div class="ea-report-icon mx-auto mb-3" style="background: rgba(30,136,229,.10); color:#1e88e5;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="ea-summary-number is-pending">51</div>
            <div class="ea-summary-label">Pendientes de revisión</div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="ea-card p-4 text-center h-100">
            <div class="ea-report-icon mx-auto mb-3" style="background: rgba(63,125,59,.12); color:#3f7d3b;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="ea-summary-number is-approved">41</div>
            <div class="ea-summary-label">Revisados</div>
        </div>
    </div>
</div>

{{-- Bloques centrales --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-6">
        <div class="ea-card p-3 p-md-4 h-100">
            <h5 class="ea-report-title mb-3">Por destino</h5>

            <div class="d-grid gap-2" style="max-height: 240px; overflow-y: auto; padding-right: 6px;">
                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Cascadas de Misol-Ha</div>
                        <div class="small" style="color: var(--ea-muted);">3 pendientes</div>
                    </div>
                    <span class="ea-chip red">5</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Cascada Velo de Novia</div>
                        <div class="small" style="color: var(--ea-muted);">3 pendientes</div>
                    </div>
                    <span class="ea-chip red">5</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Chinkultic</div>
                        <div class="small" style="color: var(--ea-muted);">3 pendientes</div>
                    </div>
                    <span class="ea-chip red">5</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Cascadas de Agua Azul</div>
                        <div class="small" style="color: var(--ea-muted);">2 pendientes</div>
                    </div>
                    <span class="ea-chip gray">4</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Cenotes de Ocosingo</div>
                        <div class="small" style="color: var(--ea-muted);">2 pendientes</div>
                    </div>
                    <span class="ea-chip gray">4</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="ea-card p-3 p-md-4 h-100">
            <h5 class="ea-report-title mb-3">Por usuario</h5>

            <div class="d-grid gap-2" style="max-height: 240px; overflow-y: auto; padding-right: 6px;">
                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ea-avatar">M</div>
                        <div>
                            <div class="fw-semibold">Marta Cruz</div>
                            <div class="small" style="color: var(--ea-muted);">Gestor de Rutas</div>
                        </div>
                    </div>
                    <span class="ea-chip gray">2</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ea-avatar">V</div>
                        <div>
                            <div class="fw-semibold">Valentina Torres</div>
                            <div class="small" style="color: var(--ea-muted);">Turista</div>
                        </div>
                    </div>
                    <span class="ea-chip gray">2</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ea-avatar">G</div>
                        <div>
                            <div class="fw-semibold">Gabriela Navarro</div>
                            <div class="small" style="color: var(--ea-muted);">Turista</div>
                        </div>
                    </div>
                    <span class="ea-chip gray">2</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ea-avatar">M</div>
                        <div>
                            <div class="fw-semibold">María Destinos</div>
                            <div class="small" style="color: var(--ea-muted);">Admin. de Destinos</div>
                        </div>
                    </div>
                    <span class="ea-chip gray">1</span>
                </div>

                <div class="ea-soft-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ea-avatar">L</div>
                        <div>
                            <div class="fw-semibold">Laura Visitante</div>
                            <div class="small" style="color: var(--ea-muted);">Turista</div>
                        </div>
                    </div>
                    <span class="ea-chip gray">1</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tarjetas de exportación --}}
<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="ea-report-card p-4 h-100">
            <div class="ea-report-icon mb-3">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <h5 class="ea-report-title mb-1">Reporte de Destinos</h5>
            <p class="ea-report-desc mb-3">Catálogo con estado y reportes</p>

            <a href="#" class="btn ea-btn-outline-green">
                <i class="bi bi-download me-2"></i>
                Exportar PDF
            </a>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="ea-report-card p-4 h-100">
            <div class="ea-report-icon mb-3">
                <i class="bi bi-file-earmark-text"></i>
            </div>

            <h5 class="ea-report-title mb-1">Reporte de Rutas</h5>
            <p class="ea-report-desc mb-3">Rutas aprobadas y publicadas</p>

            <a href="#" class="btn ea-btn-outline-green">
                <i class="bi bi-download me-2"></i>
                Exportar PDF
            </a>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="ea-report-card p-4 h-100">
            <div class="ea-report-icon mb-3">
                <i class="bi bi-bar-chart"></i>
            </div>

            <h5 class="ea-report-title mb-1">Actividad de Roles</h5>
            <p class="ea-report-desc mb-3">Contenido por autor y estado</p>

            <a href="#" class="btn ea-btn-outline-green">
                <i class="bi bi-download me-2"></i>
                Exportar PDF
            </a>
        </div>
    </div>
</div>

@endsection