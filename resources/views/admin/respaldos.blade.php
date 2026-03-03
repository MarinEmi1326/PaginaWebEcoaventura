@extends('layouts.admin')

@section('title', 'Respaldos | Ecoaventura')

@section('content')

  <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
      <h1 class="ea-page-title mb-1">Respaldo de Información</h1>
    </div>

    <a href="#"
       class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
      <i class="bi bi-database me-2"></i>
      Generar Respaldo
    </a>
  </div>

  <div class="ea-card p-0 overflow-hidden">
    <div class="table-responsive">
      <table class="table mb-0 ea-table">
        <thead>
          <tr>
            <th class="px-4 py-3">Fecha</th>
            <th class="px-4 py-3">Tamaño</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3 text-end">Acciones</th>
          </tr>
        </thead>

        <tbody>
          {{-- Fila 1 --}}
          <tr>
            <td class="px-4 py-4">2026-02-13 10:30</td>
            <td class="px-4 py-4" style="color: var(--ea-muted);">45.2 MB</td>
            <td class="px-4 py-4">
              <span class="ea-chip green">completado</span>
            </td>
            <td class="px-4 py-4 text-end">
              <a href="#" class="ea-link-download">
                <i class="bi bi-download me-2"></i> Descargar
              </a>
            </td>
          </tr>

          {{-- Fila 2 --}}
          <tr>
            <td class="px-4 py-4">2026-02-06 10:30</td>
            <td class="px-4 py-4" style="color: var(--ea-muted);">44.8 MB</td>
            <td class="px-4 py-4">
              <span class="ea-chip green">completado</span>
            </td>
            <td class="px-4 py-4 text-end">
              <a href="#" class="ea-link-download">
                <i class="bi bi-download me-2"></i> Descargar
              </a>
            </td>
          </tr>
        </tbody>

      </table>
    </div>
  </div>

@endsection