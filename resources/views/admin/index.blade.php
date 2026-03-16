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
                    <div class="fs-4 fw-bold mb-0">{{ $totalUsuarios }}</div>
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
                    <div class="fs-4 fw-bold mb-0">{{ $publicados }}</div>
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
                    <div class="fs-4 fw-bold mb-0">{{ $pendientes }}</div>
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
                    <div class="fs-4 fw-bold mb-0">{{ $rechazados }}</div>
                    <div class="small" style="color: var(--ea-muted);">Rechazados</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Paneles --}}
    <div class="row g-3">

        {{-- Cola de aprobación --}}
        <div class="col-12 col-lg-6">
            <div class="ea-card p-4">
                <h5 class="fw-semibold mb-3" style="font-family: Georgia, 'Times New Roman', serif;">Cola de aprobación</h5>

                <div class="d-grid gap-3">
                    @forelse($colaAprobacion as $sol)
                        <div class="ea-soft-row d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold">{{ $sol->nombre }} {{ $sol->apaterno }}</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    {{ $sol->rol === 'admin_destinos' ? 'Admin. de Destinos' : 'Gestor de Rutas' }}
                                    @if ($sol->fecha_solicitud)
                                        · {{ \Carbon\Carbon::parse($sol->fecha_solicitud)->format('Y-m-d') }}
                                    @endif
                                </div>
                            </div>
                            <a class="btn ea-btn-green btn-sm rounded-3 px-3"
                                href="{{ route('admin.solicitudes.show', $sol->id_usuario) }}">
                                Revisar
                            </a>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3">No hay solicitudes pendientes.</p>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Actividad reciente --}}
        <div class="col-12 col-lg-6">
            <div class="ea-card p-4">
                <h5 class="fw-semibold mb-3" style="font-family: Georgia, 'Times New Roman', serif;">Actividad reciente</h5>

                <div class="d-grid gap-3">
                    @forelse($actividadReciente as $act)
                        <div class="ea-soft-row d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold">{{ $act->nombre }}</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    Destino · {{ \Carbon\Carbon::parse($act->fecha_creacion)->format('Y-m-d') }}
                                </div>
                            </div>
                            <span class="ea-chip {{ $act->activo === 'activo' ? 'green' : 'red' }}">
                                {{ $act->activo === 'activo' ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted small text-center py-3">Sin actividad reciente.</p>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
@endsection
