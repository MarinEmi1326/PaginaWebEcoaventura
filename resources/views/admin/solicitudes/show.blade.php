@extends('layouts.admin')

@section('title', 'Detalle de solicitud')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-12">

                {{-- Header --}}
                <div class="mb-4">
                    <a href="{{ route('admin.solicitudes.index') }}" class="text-decoration-none fw-semibold"
                        style="color: var(--ea-green);">
                        ← Volver al listado
                    </a>

                    <h1 class="ea-page-title mt-3 mb-1">Detalle de Solicitud</h1>
                    <p class="ea-subtitle mb-0">Revisa la información del usuario y administra su estado.</p>
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

                @php
                    $nombreCompleto = trim(
                        ($solicitud->nombre ?? '') .
                            ' ' .
                            ($solicitud->apaterno ?? '') .
                            ' ' .
                            ($solicitud->amaterno ?? ''),
                    );
                    $estado = strtolower($solicitud->estado ?? 'pendiente');

                    $chipEstado = match ($estado) {
                        'aprobado' => 'green',
                        'rechazado' => 'red',
                        'pendiente' => 'blue',
                        default => 'gray',
                    };

                    $rolLabel = match ($solicitud->rol) {
                        'admin_destinos' => 'Admin. de Destinos',
                        'gestor_rutas' => 'Gestor de Rutas',
                        'turista' => 'Turista',
                        default => ucfirst(str_replace('_', ' ', $solicitud->rol)),
                    };

                    $esTurista = $solicitud->rol === 'turista';
                @endphp

                {{-- Card principal --}}
                <div class="ea-card p-0 overflow-hidden">

                    {{-- Encabezado principal --}}
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-start gap-3 flex-wrap"
                        style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.25);">

                        <div class="d-flex align-items-center gap-3">
                            <div class="ea-avatar" style="width: 64px; height: 64px; font-size: 1.3rem;">
                                {{ strtoupper(substr($solicitud->nombre ?? 'U', 0, 1)) }}
                            </div>

                            <div>
                                <div class="fw-semibold"
                                    style="font-family: Georgia, 'Times New Roman', serif; font-size: 1.6rem;">
                                    {{ $nombreCompleto ?: 'Sin nombre' }}
                                </div>
                                <div class="small" style="color: var(--ea-muted);">{{ $solicitud->correo }}</div>
                                <div class="small" style="color: var(--ea-muted);">
                                    Registrado:
                                    {{ $solicitud->fecha_solicitud ? \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('Y-m-d') : '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="d-flex flex-column align-items-end gap-2">
                                @if ($solicitud->activo ?? 0)
                                    <span class="ea-chip green">Activo</span>
                                @else
                                    <span class="ea-chip red">Inhabilitado</span>
                                @endif

                                <span class="ea-chip gray">{{ $rolLabel }}</span>

                                {{-- Botón suspender/habilitar solo para no-turistas --}}
                                @if (!$esTurista)
                                    <form action="{{ route('admin.solicitudes.toggle', $solicitud->id_usuario) }}"
                                        method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn rounded-3 px-3 py-2 fw-semibold"
                                            style="font-size:.85rem;
                                            {{ $solicitud->activo ?? 0
                                                ? 'background:#e4572e; border-color:#e4572e; color:#fff;'
                                                : 'background: rgba(63,125,59,.10); border:1px solid rgba(63,125,59,.30); color:#2f6b2c;' }}">
                                            @if ($solicitud->activo ?? 0)
                                                <i class="bi bi-slash-circle me-1"></i> Suspender
                                            @else
                                                <i class="bi bi-check2-circle me-1"></i> Habilitar
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- Resumen --}}
                    <div class="p-4">
                        <div class="row g-4">

                            <div class="col-12 col-md-6">
                                <div class="ea-soft-row h-100 p-4">
                                    <div class="small mb-1" style="color: var(--ea-muted);">Rol</div>
                                    <div class="fw-semibold">{{ $rolLabel }}</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="ea-soft-row h-100 p-4">
                                    <div class="small mb-1" style="color: var(--ea-muted);">Estado actual</div>
                                    <div>
                                        <span class="ea-chip {{ $chipEstado }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Bloques de información --}}
                    <div class="px-4 pb-4">
                        <div class="row g-4">

                            {{-- Datos personales --}}
                            <div class="col-12 col-md-6">
                                <div class="ea-card p-5 h-100">
                                    <div class="fw-semibold mb-3">Datos personales</div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: var(--ea-muted);">Nombre completo</span>
                                        <span class="fw-semibold">{{ $nombreCompleto ?: '—' }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: var(--ea-muted);">Correo</span>
                                        <span class="fw-semibold">{{ $solicitud->correo ?? '—' }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span style="color: var(--ea-muted);">Teléfono</span>
                                        <span class="fw-semibold">{{ $solicitud->telefono ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Datos de la solicitud --}}
                            <div class="col-12 col-md-6">
                                <div class="ea-card p-5 h-100">
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
                                        <span class="fw-semibold">{{ $solicitud->activo ?? 0 ? 'Sí' : 'No' }}</span>
                                    </div>

                                    @if (!empty($solicitud->motivo_rechazo))
                                        <div class="ea-reject-box mt-3">
                                            <div class="ea-reject-label">❗ Motivo de rechazo:</div>
                                            <div class="ea-reject-text">{{ $solicitud->motivo_rechazo }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Acciones de aprobación / rechazo — solo para admin_destinos y gestor_rutas --}}
                    @if (!$esTurista)
                        <div class="p-4 border-top" style="border-color: var(--ea-line) !important;">
                            @if ($estado === 'pendiente')
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    {{-- Aprobar --}}
                                    <form method="POST"
                                        action="{{ route('admin.solicitudes.aprobar', $solicitud->id_usuario) }}">
                                        @csrf
                                        <button type="submit" class="btn ea-btn-approve rounded-3 px-4 py-2 fw-semibold">
                                            <i class="bi bi-check2-circle me-2"></i>Aprobar
                                        </button>
                                    </form>

                                    {{-- Mostrar formulario de rechazo --}}
                                    <button type="button" class="btn rounded-3 px-4 py-2 fw-semibold"
                                        style="border:1px solid rgba(209,75,58,.35); background: rgba(209,75,58,.08); color:#d14b3a;"
                                        onclick="document.getElementById('rechazar-form').classList.remove('d-none'); document.getElementById('rechazar-form').scrollIntoView({behavior:'smooth'});">
                                        <i class="bi bi-x-circle me-2"></i>Rechazar
                                    </button>
                                </div>

                                {{-- Formulario rechazo --}}
                                <div id="rechazar-form" class="d-none mt-3 ea-card p-4">
                                    <form method="POST"
                                        action="{{ route('admin.solicitudes.rechazar', $solicitud->id_usuario) }}">
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
                    @endif

                    {{-- Reportes del usuario --}}
                    <div class="p-4 border-top" style="border-color: var(--ea-line) !important;">
                        <div class="fw-semibold mb-3" style="font-size: 1.1rem;">
                            Reportes realizados por este usuario
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: rgba(255,255,255,.25);">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Motivo</th>
                                        <th>Contra</th>
                                        <th>Estado</th>
                                        <th class="text-end">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportes as $rep)
                                        <tr>
                                            <td>
                                                @if ($rep->tipo_objeto === 'comentario')
                                                    <span class="badge bg-warning text-dark">Comentario</span>
                                                @else
                                                    <span class="badge bg-danger">Destino</span>
                                                @endif
                                            </td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $rep->motivo)) }}</td>
                                            <td style="color: var(--ea-muted);">
                                                @if ($rep->nombre_destino)
                                                    Destino: {{ $rep->nombre_destino }}
                                                @endif
                                                @if ($rep->texto_comentario)
                                                    <span
                                                        class="fst-italic">"{{ Str::limit($rep->texto_comentario, 50) }}"</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $chip = match ($rep->estado) {
                                                        'pendiente' => 'blue',
                                                        'resuelto' => 'green',
                                                        'rechazado' => 'red',
                                                        default => 'gray',
                                                    };
                                                @endphp
                                                <span
                                                    class="ea-chip {{ $chip }}">{{ ucfirst($rep->estado) }}</span>
                                            </td>
                                            <td class="text-end small text-muted">
                                                {{ \Carbon\Carbon::parse($rep->fecha)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                Este usuario no ha realizado reportes.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Reportes recibidos --}}
                    <div class="p-4 border-top" style="border-color: var(--ea-line) !important;">
                        <div class="fw-semibold mb-3" style="font-size: 1.1rem;">
                            Reportes recibidos (sus comentarios)
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: rgba(255,255,255,.25);">
                                    <tr>
                                        <th>Comentario reportado</th>
                                        <th>Destino</th>
                                        <th>Reportado por</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th class="text-end">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportesRecibidos as $rep)
                                        <tr>
                                            <td class="fst-italic text-muted">
                                                "{{ Str::limit($rep->texto_comentario, 60) }}"
                                            </td>
                                            <td>{{ $rep->nombre_destino ?? '—' }}</td>
                                            <td>{{ $rep->nombre_reporter }} {{ $rep->apaterno_reporter }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $rep->motivo)) }}</td>
                                            <td>
                                                @php
                                                    $chip = match ($rep->estado) {
                                                        'pendiente' => 'blue',
                                                        'resuelto' => 'green',
                                                        'rechazado' => 'red',
                                                        default => 'gray',
                                                    };
                                                @endphp
                                                <span
                                                    class="ea-chip {{ $chip }}">{{ ucfirst($rep->estado) }}</span>
                                            </td>
                                            <td class="text-end small text-muted">
                                                {{ \Carbon\Carbon::parse($rep->fecha)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                Este usuario no ha recibido reportes.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
