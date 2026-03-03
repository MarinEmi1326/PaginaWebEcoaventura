@extends('layouts.admin')

@section('title', 'Aprobación | Ecoaventura')

@section('content')

  <h2 class="ea-page-title mb-1">Cola de Aprobación</h2>
  <p class="ea-subtitle mb-4">
    Revisa el contenido enviado por Administradores de Destinos y Gestores de Rutas.
  </p>

  {{-- Flujo de publicación --}}
  <div class="ea-flow-box mb-4">
    <div class="fw-semibold mb-2" style="color: var(--ea-text);">Flujo de publicación:</div>

    <div class="ea-flow-chips">
      <span class="ea-chip gray">Borrador</span>
      <span style="color: var(--ea-muted);">→</span>
      <span class="ea-chip blue">Pendiente</span>
      <span style="color: var(--ea-muted);">→</span>
      <span class="ea-chip green">Aprobado</span>
      <span style="color: var(--ea-muted);">/</span>
      <span class="ea-chip red">Rechazado</span>
      <span style="color: var(--ea-muted);">→</span>
      <span class="ea-chip teal">Publicado</span>
    </div>
  </div>

  {{-- LISTA (solo vista) --}}
  <div class="d-grid gap-3">

    {{-- Item 1: Pendiente --}}
    <div class="ea-item">
      <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
          <h5 class="ea-item-title">Zona Arqueológica de Toniná</h5>
          <div class="ea-item-meta">Destino · por María Destinos (Admin. de Destinos) · 2026-02-10</div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="ea-chip blue">Pendiente</span>
          <button type="button" class="btn ea-btn-approve">
            <i class="bi bi-check2 me-2"></i> Aprobar
          </button>
          <button type="button" class="btn ea-btn-reject">
            <i class="bi bi-x-circle me-2"></i> Rechazar
          </button>
        </div>
      </div>
    </div>

    {{-- Item 2: Pendiente --}}
    <div class="ea-item">
      <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
          <h5 class="ea-item-title">Ruta de Cascadas</h5>
          <div class="ea-item-meta">Ruta · por Pedro Rutas (Gestor de Rutas) · 2026-02-08</div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="ea-chip blue">Pendiente</span>
          <button type="button" class="btn ea-btn-approve">
            <i class="bi bi-check2 me-2"></i> Aprobar
          </button>
          <button type="button" class="btn ea-btn-reject">
            <i class="bi bi-x-circle me-2"></i> Rechazar
          </button>
        </div>
      </div>
    </div>

    {{-- Item 3: Aprobado (puede publicar) --}}
    <div class="ea-item">
      <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
          <h5 class="ea-item-title">Cascadas de Agua Azul</h5>
          <div class="ea-item-meta">Destino · por María Destinos (Admin. de Destinos) · 2026-02-05</div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="ea-chip green">Aprobado</span>
          <button type="button" class="btn ea-btn-publish">
            <i class="bi bi-eye me-2"></i> Publicar
          </button>
        </div>
      </div>
    </div>

    {{-- Item 4: Publicado --}}
    <div class="ea-item">
      <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
          <h5 class="ea-item-title">Ruta Arqueológica Maya</h5>
          <div class="ea-item-meta">Ruta · por Pedro Rutas (Gestor de Rutas) · 2026-01-28</div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="ea-chip teal">Publicado</span>
        </div>
      </div>
    </div>

    {{-- Item 5: Rechazado + motivo --}}
    <div class="ea-item">
      <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
          <h5 class="ea-item-title">Selva Lacandona</h5>
          <div class="ea-item-meta">Destino · por María Destinos (Admin. de Destinos) · 2026-02-06</div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <span class="ea-chip red">Rechazado</span>
        </div>
      </div>

      <div class="ea-reject-box">
        <div class="ea-reject-label">
          <i class="bi bi-exclamation-circle"></i>
          Motivo de rechazo:
        </div>
        <div class="ea-reject-text">
          Falta información de cooperativas locales.
        </div>
      </div>
    </div>

  </div>

@endsection