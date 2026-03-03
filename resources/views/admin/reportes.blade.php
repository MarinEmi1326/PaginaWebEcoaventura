@extends('layouts.admin')

@section('title', 'Reportes del Sistema')

@section('content')

  <div class="mb-4">
    <h1 class="ea-page-title mb-1">Reportes del Sistema</h1>
  </div>

  <div class="row g-3">
    {{-- Card 1 --}}
    <div class="col-12 col-lg-4">
      <div class="ea-report-card p-4 h-100">
        <div class="ea-report-icon mb-3">
          <i class="bi bi-graph-up-arrow"></i>
        </div>

        <h5 class="ea-report-title mb-1">Destinos Turísticos</h5>
        <p class="ea-report-desc mb-3">Catálogo de destinos registrados</p>

        <a href="#" class="btn ea-btn-outline-green">
          <i class="bi bi-download me-2"></i>
          Exportar PDF
        </a>
      </div>
    </div>

    {{-- Card 2 --}}
    <div class="col-12 col-lg-4">
      <div class="ea-report-card p-4 h-100">
        <div class="ea-report-icon mb-3">
          <i class="bi bi-file-earmark-text"></i>
        </div>

        <h5 class="ea-report-title mb-1">Rutas Publicadas</h5>
        <p class="ea-report-desc mb-3">Rutas aprobadas y visibles</p>

        <a href="#" class="btn ea-btn-outline-green">
          <i class="bi bi-download me-2"></i>
          Exportar PDF
        </a>
      </div>
    </div>

    {{-- Card 3 --}}
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