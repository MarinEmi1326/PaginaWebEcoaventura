@extends('layouts.admin')

@section('title', 'Ecoaventura | Administrador de Destinos')

@section('content')

    {{-- Cards resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="ea-card ea-card-tight text-center">
                <div class="ea-summary-number">4</div>
                <div class="ea-summary-label">Total</div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="ea-card ea-card-tight text-center">
                <div class="ea-summary-number is-approved">1</div>
                <div class="ea-summary-label">Aprobados</div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="ea-card ea-card-tight text-center">
                <div class="ea-summary-number is-pending">1</div>
                <div class="ea-summary-label">Pendientes</div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="ea-card ea-card-tight text-center">
                <div class="ea-summary-number is-rejected">1</div>
                <div class="ea-summary-label">Rechazados</div>
            </div>
        </div>
    </div>

  
    {{-- Encabezado --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h2 class="ea-section-heading">Mis Destinos</h2>

        <a href="#" class="btn ea-btn-green">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Destino
        </a>
    </div>

    {{-- Lista de destinos --}}
    <div class="d-grid gap-3">

        <div class="ea-destination-card">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <div class="ea-destination-title">Zona Arqueológica de Toniná</div>
                    <div class="ea-destination-meta">Categoría: arqueológicos · Actualizado: 2026-02-10</div>
                </div>

                <div class="d-flex align-items-center gap-3 ms-lg-auto">
                    <span class="ea-status approved">Aprobado</span>

                    <div class="ea-actions">
                        <a href="#" class="ea-action-btn edit" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="#" class="ea-action-btn delete" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="ea-destination-card">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <div class="ea-destination-title">Cascadas de Agua Azul</div>
                    <div class="ea-destination-meta">Categoría: cascadas · Actualizado: 2026-02-08</div>
                </div>

                <div class="d-flex align-items-center gap-3 ms-lg-auto">
                    <span class="ea-status pending">Pendiente de aprobación</span>

                    <div class="ea-actions">
                        <a href="#" class="ea-action-btn edit" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="#" class="ea-action-btn delete" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="ea-destination-card">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="ea-destination-title">Selva Lacandona</div>
                    <div class="ea-destination-meta">Categoría: ecoturismo · Actualizado: 2026-02-06</div>

                    <div class="ea-reject-box">
                        <div class="ea-reject-label">
                            <i class="bi bi-exclamation-circle"></i>
                            Motivo de rechazo:
                        </div>
                        <div class="ea-reject-text">
                            Falta incluir información de acceso y contacto de cooperativas locales.
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 ms-lg-auto">
                    <span class="ea-status rejected">Rechazado</span>

                    <div class="ea-actions">
                        <a href="#" class="ea-action-btn edit" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="#" class="ea-action-btn send" title="Enviar de nuevo">
                            <i class="bi bi-send"></i>
                        </a>
                        <a href="#" class="ea-action-btn delete" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="ea-destination-card">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                <div>
                    <div class="ea-destination-title">Laguna Miramar</div>
                    <div class="ea-destination-meta">Categoría: ecoturismo · Actualizado: 2026-02-11</div>
                </div>

                <div class="d-flex align-items-center gap-3 ms-lg-auto">
                    <span class="ea-status draft">Borrador</span>

                    <div class="ea-actions">
                        <a href="#" class="ea-action-btn edit" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="#" class="ea-action-btn send" title="Enviar">
                            <i class="bi bi-send"></i>
                        </a>
                        <a href="#" class="ea-action-btn delete" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection