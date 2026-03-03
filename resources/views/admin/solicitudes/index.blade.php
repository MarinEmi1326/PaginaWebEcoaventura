@extends('layouts.admin')

@section('title', 'Gestión de Solicitudes y Usuarios')

@section('content')

  {{-- Encabezado --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
      <h1 class="ea-page-title mb-1">Solicitudes y Usuarios</h1>
      <p class="ea-subtitle mb-0">Historial completo y gestión de estados de cuenta.</p>
    </div>

    <div class="d-flex gap-2">
      <a href="#"
         class="btn btn-dark rounded-3 px-3 py-2 fw-semibold">
        <i class="bi bi-bar-chart-line me-2"></i>
        Generar Reporte
      </a>

      <a href="{{ route('admin.solicitudes.create') }}"
         class="btn ea-btn-green rounded-3 px-3 py-2 fw-semibold">
        <i class="bi bi-plus-lg me-2"></i>
        Crear Usuario
      </a>
    </div>
  </div>

  {{-- Mensaje --}}
  @if (session('ok'))
    <div class="alert alert-success border-0 rounded-4 mb-4" style="background: rgba(63,125,59,.12); color:#2f6b2c;">
      <i class="bi bi-check-circle me-2"></i>{{ session('ok') }}
    </div>
  @endif

  {{-- Contenedor --}}
  <div class="ea-card p-0 overflow-hidden">

    <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="fw-semibold" style="font-family: Georgia, 'Times New Roman', serif; font-size: 1.25rem;">
          Registros Totales ({{ $solicitudes->count() }})
        </div>

        {{-- (opcional) mini filtro visual --}}
        <div class="small" style="color: var(--ea-muted);">
          <i class="bi bi-info-circle me-1"></i>Vista administrativa
        </div>
      </div>
    </div>

    @if($solicitudes->count() === 0)
      <div class="p-5 text-center">
        <div class="fs-1 mb-2">📂</div>
        <div class="fw-semibold">No se encontraron registros</div>
        <div class="small" style="color: var(--ea-muted);">Cuando haya solicitudes, aparecerán aquí.</div>
      </div>
    @else

      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead style="background: rgba(255,255,255,.25);">
            <tr class="border-bottom" style="border-color: var(--ea-line) !important;">
              <th class="px-4 py-3 text-uppercase small" style="color: var(--ea-muted); letter-spacing: .08em;">Solicitante</th>
              <th class="px-4 py-3 text-uppercase small" style="color: var(--ea-muted); letter-spacing: .08em;">Tipo / Rol</th>
              <th class="px-4 py-3 text-uppercase small text-center" style="color: var(--ea-muted); letter-spacing: .08em;">Fecha registro</th>
              <th class="px-4 py-3 text-uppercase small text-center" style="color: var(--ea-muted); letter-spacing: .08em;">Estado</th>
              <th class="px-4 py-3 text-uppercase small text-end" style="color: var(--ea-muted); letter-spacing: .08em;">Acciones</th>
            </tr>
          </thead>

          <tbody>
            @foreach($solicitudes as $s)
              @php
                $nombreCompleto = trim(($s->nombre ?? '').' '.($s->apaterno ?? '').' '.($s->amaterno ?? ''));
                $fecha = $s->fecha_solicitud ? \Carbon\Carbon::parse($s->fecha_solicitud)->format('d/m/Y') : '—';
              @endphp

              <tr class="border-bottom" style="border-color: rgba(15,42,36,.06) !important;">
                {{-- Nombre/Correo --}}
                <td class="px-4 py-3">
                  <div class="fw-bold {{ !$s->activo ? 'text-secondary text-decoration-line-through fst-italic' : '' }}">
                    {{ $nombreCompleto ?: 'Sin Nombre' }}
                  </div>
                  <div class="small" style="color: var(--ea-muted);">{{ $s->correo }}</div>
                </td>

                {{-- Rol --}}
                <td class="px-4 py-3">
                  <span class="ea-chip gray text-capitalize">
                    <i class="bi bi-person-badge me-1"></i>{{ $s->rol }}
                  </span>
                </td>

                {{-- Fecha --}}
                <td class="px-4 py-3 text-center" style="color: var(--ea-muted);">
                  {{ $fecha }}
                </td>

                {{-- Estado --}}
                <td class="px-4 py-3 text-center">
                  @if(!$s->activo)
                    <span class="ea-chip red">
                      <i class="bi bi-exclamation-triangle me-1"></i>Inhabilitado
                    </span>
                  @else
                    @php
                      $estado = strtolower($s->estado ?? 'pendiente');
                      $chipClass = match($estado){
                        'aprobado' => 'green',
                        'rechazado' => 'red',
                        'pendiente' => 'blue',
                        default => 'gray'
                      };
                    @endphp
                    <span class="ea-chip {{ $chipClass }}">
                      {{ ucfirst($estado) }}
                    </span>
                  @endif
                </td>

                {{-- Acciones --}}
                <td class="px-4 py-3">
                  <div class="d-flex justify-content-end gap-2 flex-wrap">

                    {{-- Suspender / Habilitar --}}
                    <form action="{{ route('admin.solicitudes.toggle', $s->id_usuario) }}" method="POST">
                      @csrf
                      <button type="submit"
                              class="btn rounded-3 px-3 py-2 fw-semibold"
                              style="
                                font-size:.85rem;
                                {{ $s->activo
                                  ? 'border:1px solid rgba(209,75,58,.35); background: rgba(209,75,58,.08); color:#d14b3a;'
                                  : 'border:1px solid rgba(63,125,59,.30); background: rgba(63,125,59,.10); color:#2f6b2c;'
                                }}
                              ">
                        @if($s->activo)
                          <i class="bi bi-slash-circle me-2"></i>Suspender
                        @else
                          <i class="bi bi-check2-circle me-2"></i>Habilitar
                        @endif
                      </button>
                    </form>

                    {{-- Editar --}}
                    <a href="{{ route('admin.solicitudes.edit', $s->id_usuario) }}"
                       class="btn rounded-3 px-3 py-2 fw-semibold"
                       style="font-size:.85rem; border:1px solid rgba(30,136,229,.25); background: rgba(30,136,229,.08); color:#1e88e5;">
                      <i class="bi bi-pencil-square me-2"></i>Editar
                    </a>

                    {{-- Ver --}}
                    <a href="{{ route('admin.solicitudes.show', $s->id_usuario) }}"
                       class="btn rounded-3 px-3 py-2 fw-semibold"
                       style="font-size:.85rem; border:1px solid rgba(15,90,58,.18); background: rgba(15,90,58,.08); color: var(--ea-green);">
                      <i class="bi bi-eye me-2"></i>Ver
                    </a>

                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>

        </table>
      </div>

    @endif
  </div>

@endsection