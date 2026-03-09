@extends('layouts.admin')

@section('title', 'Detalle de solicitud')

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">

      {{-- Header --}}
      <div class="mb-4">
        <a href="{{ route('admin.solicitudes.index') }}"
           class="text-decoration-none fw-semibold"
           style="color: var(--ea-green);">
          ← Regresar a solicitudes
        </a>

        <h1 class="ea-page-title mt-3 mb-1">Detalle de Solicitud</h1>
        <p class="ea-subtitle mb-0">Revisa la información y aprueba o rechaza la solicitud.</p>
      </div>

      {{-- Mensajes --}}
      @if (session('ok'))
        <div class="alert alert-success border-0 rounded-4"
             style="background: rgba(63,125,59,.12); color:#2f6b2c;">
          {{ session('ok') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger border-0 rounded-4"
             style="background: rgba(209,75,58,.10); color:#d14b3a;">
          {{ session('error') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4"
             style="background: rgba(209,75,58,.10); color:#d14b3a;">
          <div class="fw-semibold mb-2">Revisa los campos:</div>
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Card principal --}}
      <div class="ea-card p-0 overflow-hidden">

        <div class="p-4 border-bottom d-flex justify-content-between align-items-start gap-3 flex-wrap"
             style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">

          <div>
            <div class="small" style="color: var(--ea-muted);">Solicitante</div>
            <div class="fw-semibold" style="font-family: Georgia, 'Times New Roman', serif; font-size: 1.6rem;">
              {{ trim(($solicitud->nombre ?? '') . ' ' . ($solicitud->apaterno ?? '') . ' ' . ($solicitud->amaterno ?? '')) }}
            </div>
            <div class="small" style="color: var(--ea-muted);">{{ $solicitud->correo }}</div>
          </div>

          <div class="text-end">
            <div class="small" style="color: var(--ea-muted);">Tipo</div>
            <span class="ea-chip gray">
              {{ $solicitud->rol === 'admin_destinos' ? 'Administrador de destinos' : 'Gestor de rutas' }}
            </span>

            <div class="small mt-3" style="color: var(--ea-muted);">Estado</div>
            @php $estado = strtolower($solicitud->estado ?? 'pendiente'); @endphp
            <span class="ea-chip
              {{ $estado === 'aprobado' ? 'green' : '' }}
              {{ $estado === 'rechazado' ? 'red' : '' }}
              {{ $estado === 'pendiente' ? 'blue' : '' }}
              {{ !in_array($estado, ['aprobado', 'rechazado', 'pendiente']) ? 'gray' : '' }}">
              {{ ucfirst($estado) }}
            </span>
          </div>

        </div>

        <div class="p-4">
          <div class="row g-3">

            {{-- Datos personales --}}
            <div class="col-12 col-md-6">
              <div class="ea-card p-4">
                <div class="fw-semibold mb-3">Datos personales</div>

                <div class="d-flex justify-content-between mb-2">
                  <span style="color: var(--ea-muted);">Nombre completo</span>
                  <span class="fw-semibold">
                    {{ trim(($solicitud->nombre ?? '') . ' ' . ($solicitud->apaterno ?? '') . ' ' . ($solicitud->amaterno ?? '')) ?: '—' }}
                  </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span style="color: var(--ea-muted);">Correo</span>
                  <span class="fw-semibold">{{ $solicitud->correo ?? '—' }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span style="color: var(--ea-muted);">Teléfono</span>
                  <span class="fw-semibold">{{ $solicitud->telefono ?? '—' }}</span>
                </div>

                <div class="d-flex justify-content-between">
                  <span style="color: var(--ea-muted);">Rol solicitado</span>
                  <span class="fw-semibold">
                    {{ $solicitud->rol === 'admin_destinos' ? 'Administrador de destinos' : 'Gestor de rutas' }}
                  </span>
                </div>
              </div>
            </div>

            {{-- Datos de la solicitud --}}
            <div class="col-12 col-md-6">
              <div class="ea-card p-4">
                <div class="fw-semibold mb-3">Datos de la solicitud</div>

                <div class="d-flex justify-content-between mb-2">
                  <span style="color: var(--ea-muted);">Fecha solicitud</span>
                  <span class="fw-semibold">
                    {{ $solicitud->fecha_solicitud ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') : '—' }}
                  </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span style="color: var(--ea-muted);">Fecha respuesta</span>
                  <span class="fw-semibold">
                    {{ $solicitud->fecha_respuesta ? \Carbon\Carbon::parse($solicitud->fecha_respuesta)->format('d/m/Y H:i') : '—' }}
                  </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span style="color: var(--ea-muted);">Estado</span>
                  <span class="fw-semibold">{{ ucfirst($solicitud->estado ?? '—') }}</span>
                </div>

                <div class="d-flex justify-content-between">
                  <span style="color: var(--ea-muted);">Activo</span>
                  <span class="fw-semibold">{{ ($solicitud->activo ?? 0) ? 'Sí' : 'No' }}</span>
                </div>

                @if(!empty($solicitud->motivo_rechazo))
                  <div class="ea-reject-box mt-3">
                    <div class="ea-reject-label">❗ Motivo de rechazo:</div>
                    <div class="ea-reject-text">{{ $solicitud->motivo_rechazo }}</div>
                  </div>
                @endif
              </div>
            </div>

          </div>
        </div>

        {{-- Acciones --}}
        <div class="p-4 border-top" style="border-color: var(--ea-line) !important;">
          @if(($solicitud->estado ?? 'pendiente') === 'pendiente')

            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
              {{-- Aprobar --}}
              <form method="POST" action="{{ route('admin.solicitudes.aprobar', $solicitud->id_usuario) }}">
                @csrf
                <button type="submit" class="btn ea-btn-approve rounded-3 px-4 py-2 fw-semibold">
                  Aprobar
                </button>
              </form>

              {{-- Rechazar --}}
              <button type="button"
                      class="btn rounded-3 px-4 py-2 fw-semibold"
                      style="border:1px solid rgba(209,75,58,.35); background: rgba(209,75,58,.08); color:#d14b3a;"
                      onclick="document.getElementById('rechazar-form').classList.remove('d-none'); document.getElementById('rechazar-form').scrollIntoView({behavior:'smooth'});">
                Rechazar
              </button>
            </div>

            {{-- Form rechazo --}}
            <div id="rechazar-form" class="d-none mt-3 ea-card p-4">
              <form method="POST" action="{{ route('admin.solicitudes.rechazar', $solicitud->id_usuario) }}">
                @csrf

                <label class="form-label fw-semibold">
                  Motivo de rechazo <span class="text-danger">*</span>
                </label>

                <textarea name="motivo_rechazo" rows="4" class="form-control rounded-3"></textarea>

                <div class="d-flex justify-content-end mt-3">
                  <button type="submit"
                          onclick="return confirm('¿Seguro que deseas rechazar esta solicitud?')"
                          class="btn rounded-3 px-4 py-2 fw-semibold"
                          style="background:#d14b3a; border-color:#d14b3a; color:#fff;">
                    Confirmar rechazo
                  </button>
                </div>
              </form>
            </div>

          @else
            <div class="small" style="color: var(--ea-muted);">
              Esta solicitud ya fue atendida ({{ $solicitud->estado }}).
            </div>
          @endif
        </div>

      </div>

    </div>
  </div>
</div>

@endsection