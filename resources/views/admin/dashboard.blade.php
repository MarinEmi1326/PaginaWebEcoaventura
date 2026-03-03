@extends('layouts.admin')

@section('content')



  {{-- Cards métricas --}}
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="ea-card p-4 d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;background:rgba(15,90,58,.10);">
          <i class="bi bi-people" style="color: var(--ea-green);"></i>
        </div>
        <div>
          <div class="fs-4 fw-bold mb-0">4</div>
          <div class="small" style="color: var(--ea-muted);">Usuarios</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="ea-card p-4 d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;background:rgba(15,90,58,.10);">
          <i class="bi bi-check2-circle" style="color: var(--ea-green);"></i>
        </div>
        <div>
          <div class="fs-4 fw-bold mb-0">1</div>
          <div class="small" style="color: var(--ea-muted);">Publicados</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="ea-card p-4 d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;background:rgba(15,90,58,.10);">
          <i class="bi bi-clock" style="color:#1e88e5;"></i>
        </div>
        <div>
          <div class="fs-4 fw-bold mb-0">2</div>
          <div class="small" style="color: var(--ea-muted);">Pendientes</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
      <div class="ea-card p-4 d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:40px;height:40px;background:rgba(209,75,58,.12);">
          <i class="bi bi-exclamation-circle" style="color:#d14b3a;"></i>
        </div>
        <div>
          <div class="fs-4 fw-bold mb-0">1</div>
          <div class="small" style="color: var(--ea-muted);">Rechazados</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Paneles --}}
  <div class="row g-3">
    <div class="col-12 col-lg-6">
      <div class="ea-card p-4">
        <h5 class="fw-semibold mb-3" style="font-family: Georgia, 'Times New Roman', serif;">Cola de aprobación</h5>

        <div class="d-grid gap-3">
          <div class="ea-soft-row d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-semibold">Zona Arqueológica de Toniná</div>
              <div class="small" style="color: var(--ea-muted);">Destino · María Destinos</div>
            </div>
            <a class="btn ea-btn-green" href="#">Revisar</a>
          </div>

          <div class="ea-soft-row d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-semibold">Ruta de Cascadas</div>
              <div class="small" style="color: var(--ea-muted);">Ruta · Pedro Rutas</div>
            </div>
            <a class="btn ea-btn-green" href="#">Revisar</a>
          </div>
        </div>

      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="ea-card p-4">
        <h5 class="fw-semibold mb-3" style="font-family: Georgia, 'Times New Roman', serif;">Actividad reciente</h5>

        <div class="d-grid gap-3">
          <div class="ea-soft-row d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-semibold">Zona Arqueológica de Toniná</div>
              <div class="small" style="color: var(--ea-muted);">Destino · 2026-02-10</div>
            </div>
            <span class="ea-status" style="color:#1e88e5;">Pendiente</span>
          </div>

          <div class="ea-soft-row d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-semibold">Ruta de Cascadas</div>
              <div class="small" style="color: var(--ea-muted);">Ruta · 2026-02-08</div>
            </div>
            <span class="ea-status" style="color:#1e88e5;">Pendiente</span>
          </div>

          <div class="ea-soft-row d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-semibold">Cascadas de Agua Azul</div>
              <div class="small" style="color: var(--ea-muted);">Destino · 2026-02-05</div>
            </div>
            <span class="ea-status" style="color:#3f7d3b;">Aprobado</span>
          </div>

          <div class="ea-soft-row d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-semibold">Ruta Arqueológica Maya</div>
              <div class="small" style="color: var(--ea-muted);">Ruta · 2026-01-28</div>
            </div>
            <span class="ea-status" style="color:#0f5a3a;">Publicado</span>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection