@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')

<div class="mb-4">
    <h1 class="ea-page-title mb-1">Gestión de Usuarios</h1>
</div>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
    <div></div>

    <div class="d-flex flex-column flex-sm-row gap-2">
        <div style="min-width: 260px;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-3">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       class="form-control border-start-0 rounded-end-3"
                       placeholder="Buscar usuario...">
            </div>
        </div>

        <select class="form-select rounded-3" style="min-width: 170px;">
            <option>Todos los roles</option>
            <option>Admin. de Destinos</option>
            <option>Gestor de Rutas</option>
            <option>Turista</option>
        </select>
    </div>
</div>

@if (session('ok'))
    <div class="alert alert-success border-0 rounded-4 mb-4" style="background: rgba(63,125,59,.12); color:#2f6b2c;">
        <i class="bi bi-check-circle me-2"></i>{{ session('ok') }}
    </div>
@endif

<div class="ea-card p-0 overflow-hidden">

    @if($solicitudes->count() === 0)
        <div class="p-5 text-center">
            <div class="fs-1 mb-2">📂</div>
            <div class="fw-semibold">No se encontraron usuarios</div>
            <div class="small" style="color: var(--ea-muted);">Cuando haya registros, aparecerán aquí.</div>
        </div>
    @else

        <div class="table-responsive">
            <table class="table ea-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th class="text-center">Reportes</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($solicitudes as $s)
                        @php
                            $nombreCompleto = trim(($s->nombre ?? '').' '.($s->apaterno ?? '').' '.($s->amaterno ?? ''));
                            $inicial = strtoupper(substr($s->nombre ?? 'U', 0, 1));
                            $rolLabel = match($s->rol) {
                                'admin_destinos' => 'Admin. de Destinos',
                                'gestor_rutas' => 'Gestor de Rutas',
                                'turista' => 'Turista',
                                default => ucfirst(str_replace('_', ' ', $s->rol))
                            };

                            $estado = strtolower($s->estado ?? 'pendiente');
                            $chipClass = match($estado) {
                                'aprobado' => 'green',
                                'rechazado' => 'red',
                                'pendiente' => 'blue',
                                default => 'gray'
                            };

                            $reportes = $s->reportes ?? 0;
                        @endphp

                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="ea-avatar">{{ $inicial }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $nombreCompleto ?: 'Sin nombre' }}</div>
                                        <div class="small" style="color: var(--ea-muted);">{{ $s->correo }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $rolLabel }}</td>

                            <td class="text-center">
                                <span class="ea-chip gray">{{ $reportes }}</span>
                            </td>

                            <td class="text-center">
                                @if(!$s->activo)
                                    <span class="ea-chip red">Inhabilitado</span>
                                @else
                                    <span class="ea-chip {{ $chipClass }}">
                                        {{ $estado === 'aprobado' ? 'Activo' : ucfirst($estado) }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-3">

                                    <a href="{{ route('admin.solicitudes.edit', $s->id_usuario) }}"
                                       class="text-decoration-none"
                                       style="color:#1e88e5;">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Editar
                                    </a>

                                    <a href="{{ route('admin.solicitudes.show', $s->id_usuario) }}"
                                       class="text-decoration-none"
                                       style="color: var(--ea-text);">
                                        <i class="bi bi-eye me-1"></i>
                                        Ver
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-3 px-md-4 py-3 border-top"
             style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.08);">
            <div class="small" style="color: var(--ea-muted);">
                Mostrando 1–{{ $solicitudes->count() }} de {{ $solicitudes->count() }}
            </div>

            <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                <button class="btn btn-sm border-0" style="color: var(--ea-muted);">‹</button>
                <button class="btn btn-sm rounded-circle"
                        style="width:32px; height:32px; background: var(--ea-green); color:#fff;">1</button>
                <button class="btn btn-sm border-0" style="color: var(--ea-text);">2</button>
                <button class="btn btn-sm border-0" style="color: var(--ea-text);">3</button>
                <button class="btn btn-sm border-0" style="color: var(--ea-muted);">›</button>
            </div>
        </div>

    @endif
</div>

<div class="mt-4 d-flex justify-content-end">
    <a href="{{ route('admin.solicitudes.create') }}"
       class="btn ea-btn-green rounded-3 px-4 py-2 fw-semibold">
        <i class="bi bi-plus-lg me-2"></i>
        Crear Usuario
    </a>
</div>

@endsection