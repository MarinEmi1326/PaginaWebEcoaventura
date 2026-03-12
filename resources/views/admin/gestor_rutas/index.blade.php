@extends('layouts.admin')

@section('title', 'Panel de Rutas')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">

            {{-- Encabezado --}}
            <div class="mb-4">
                <h1 class="ea-page-title mb-1">Mis Rutas</h1>
                <p class="ea-subtitle mb-0">
                    Administra y consulta tus rutas registradas.
                </p>
            </div>

            {{-- Resumen --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="ea-card p-4 text-center h-100">
                        <div class="ea-summary-number">3</div>
                        <div class="ea-summary-label">Rutas registradas</div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="ea-card p-4 text-center h-100">
                        <div class="ea-summary-number">2</div>
                        <div class="ea-summary-label">Actualizadas este mes</div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="ea-card p-4 text-center h-100">
                        <div class="ea-summary-number">1</div>
                        <div class="ea-summary-label">Nuevas rutas</div>
                    </div>
                </div>
            </div>

            {{-- Acción superior --}}
            <div class="d-flex justify-content-end mb-3">
                <a href="#" class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
                    <i class="bi bi-plus-lg me-2"></i>
                    Nueva Ruta
                </a>
            </div>

            {{-- Card principal --}}
            <div class="ea-card p-0 overflow-hidden">

                <div class="p-4 border-bottom"
                     style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.18);">
                    <div class="fw-semibold" style="font-size: 1.1rem;">Mis rutas registradas</div>
                </div>

                <div class="p-3 p-md-4">

                    {{-- Ruta 1 --}}
                    <div class="ea-card p-4 mb-3">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                            <div>
                                <div class="fw-semibold" style="font-size: 1.1rem;">Ruta Arqueológica Maya</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    2 días · Moderada · Actualizado: 2026-02-05
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                    <i class="bi bi-pencil-square me-1"></i> Editar
                                </a>

                                <a href="#" class="text-decoration-none" style="color:#d14b3a;">
                                    <i class="bi bi-trash me-1"></i> Eliminar
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Ruta 2 --}}
                    <div class="ea-card p-4 mb-3">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                            <div>
                                <div class="fw-semibold" style="font-size: 1.1rem;">Ruta de Cascadas</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    1 día · Fácil · Actualizado: 2026-02-07
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                    <i class="bi bi-pencil-square me-1"></i> Editar
                                </a>

                                <a href="#" class="text-decoration-none" style="color:#d14b3a;">
                                    <i class="bi bi-trash me-1"></i> Eliminar
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Ruta 3 --}}
                    <div class="ea-card p-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                            <div>
                                <div class="fw-semibold" style="font-size: 1.1rem;">Ruta Comunitaria Lacandona</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    3 días · Alta · Actualizado: 2026-02-09
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <a href="#" class="text-decoration-none" style="color: var(--ea-text);">
                                    <i class="bi bi-pencil-square me-1"></i> Editar
                                </a>

                                <a href="#" class="text-decoration-none" style="color:#d14b3a;">
                                    <i class="bi bi-trash me-1"></i> Eliminar
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection