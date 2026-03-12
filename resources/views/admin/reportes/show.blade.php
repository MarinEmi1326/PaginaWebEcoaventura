@extends('layouts.admin')

@section('title', 'Detalle del Reporte')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.reportes') }}"
       class="text-decoration-none fw-semibold"
       style="color: var(--ea-green);">
        ← Volver al listado
    </a>
</div>

<div class="ea-card p-4">

    {{-- Encabezado --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="ea-page-title mb-1">Cascadas de Misol-Ha</h1>
            <p class="ea-subtitle mb-0">Ecoturismo · Ocosingo, Chiapas</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="ea-chip green">Activo</span>

            <button type="button"
                    class="btn rounded-3 px-4 py-2 fw-semibold"
                    style="background:#e4572e; border-color:#e4572e; color:#fff;">
                <i class="bi bi-slash-circle me-1"></i>
                Suspender
            </button>
        </div>
    </div>

    {{-- Resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="ea-soft-row text-center">
                <div class="ea-summary-number">5</div>
                <div class="ea-summary-label">Reportes totales</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="ea-soft-row text-center">
                <div class="ea-summary-number is-rejected">3</div>
                <div class="ea-summary-label">Pendientes</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="ea-soft-row text-center">
                <div class="ea-summary-number is-approved">2</div>
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
                        <th>Motivo</th>
                        <th>Reportado por</th>
                        <th>Comentario</th>
                        <th>Fecha comentario</th>
                        <th>Fecha reporte</th>
                        <th>Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Información incorrecta</td>
                        <td>Usuario 1</td>
                        <td>El horario publicado no coincide con el horario real del destino.</td>
                        <td>2026-01-09</td>
                        <td>2026-01-10</td>
                        <td><span class="ea-chip green">Revisado</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm ea-btn-outline-green rounded-3 px-3">
                                <i class="bi bi-eye me-1"></i> Ver
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Contenido ofensivo</td>
                        <td>Ana López</td>
                        <td>El comentario incluye lenguaje ofensivo hacia la comunidad local.</td>
                        <td>2026-01-10</td>
                        <td>2026-01-11</td>
                        <td><span class="ea-chip blue">Pendiente</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm ea-btn-outline-green rounded-3 px-3">
                                <i class="bi bi-eye me-1"></i> Ver
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Fotos inapropiadas</td>
                        <td>Roberto Sánchez</td>
                        <td>Las imágenes subidas no corresponden al destino y confunden al visitante.</td>
                        <td>2026-01-11</td>
                        <td>2026-01-12</td>
                        <td><span class="ea-chip blue">Pendiente</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm ea-btn-outline-green rounded-3 px-3">
                                <i class="bi bi-eye me-1"></i> Ver
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>Spam o publicidad</td>
                        <td>Marta Cruz</td>
                        <td>El comentario contiene enlaces promocionales ajenos al sistema.</td>
                        <td>2026-02-12</td>
                        <td>2026-02-13</td>
                        <td><span class="ea-chip green">Revisado</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm ea-btn-outline-green rounded-3 px-3">
                                <i class="bi bi-eye me-1"></i> Ver
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>5</td>
                        <td>Ubicación errónea</td>
                        <td>Jorge Díaz</td>
                        <td>La ubicación señalada en el comentario no coincide con el punto real del mapa.</td>
                        <td>2026-02-13</td>
                        <td>2026-02-14</td>
                        <td><span class="ea-chip blue">Pendiente</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm ea-btn-outline-green rounded-3 px-3">
                                <i class="bi bi-eye me-1"></i> Ver
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection