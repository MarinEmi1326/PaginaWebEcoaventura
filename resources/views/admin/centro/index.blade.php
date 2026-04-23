@extends('layouts.admin')

@section('title', 'Gestión de Destinos')

@section('content')

<div class="mb-4">
    <h1 class="ea-page-title mb-1">Gestión de Destinos</h1>
    <p class="ea-subtitle">Administra y supervisa todos los destinos registrados en el sistema.</p>
</div>

@if (session('success'))
    <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
@endif

<div class="ea-card p-0 overflow-hidden">

    <div class="table-responsive">
        <table class="table ea-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>Creado por</th>
                    <th>Fecha creación</th>
                    <th>Categorías</th>
                    <th class="text-center">Reportes</th>
                    <th class="text-center">Estado</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($destinos as $d)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $d->nombre }}</div>
                        </td>
                        <td>
                            <div class="small">{{ $d->creador }}</div>
                        </td>
                        <td>
                            <div class="small">
                                {{ \Carbon\Carbon::parse($d->fecha_creacion)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="small" style="color: var(--ea-muted);">
                                {{ $d->categorias ?? '—' }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="ea-chip {{ $d->reportes_pendientes > 0 ? 'red' : 'gray' }}">
                                <i class="bi bi-flag me-1"></i>{{ $d->total_reportes }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="ea-chip {{ $d->activo === 'activo' ? 'green' : 'red' }}">
                                {{ $d->activo === 'activo' ? 'Activo' : 'Suspendido' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center gap-3">

                                <a href="{{ route('admin.destino.show', $d->id_destino) }}"
                                   class="text-decoration-none" style="color: var(--ea-text);">
                                    <i class="bi bi-eye me-1"></i> Ver
                                </a>

                               

                                <form action="{{ route('admin.destino.toggle', $d->id_destino) }}" method="POST">
                                    @csrf
                                    @if ($d->activo === 'activo')
                                        <button type="submit" class="btn btn-link text-decoration-none p-0"
                                                style="color:#e4572e;">
                                            <i class="bi bi-slash-circle me-1"></i> Suspender
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-link text-decoration-none p-0"
                                                style="color:var(--ea-green);">
                                            <i class="bi bi-check-circle me-1"></i> Activar
                                        </button>
                                    @endif
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay destinos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center"
         style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.08);">
        <div class="small" style="color: var(--ea-muted);">
            Total: {{ count($destinos) }} destino(s)
        </div>
    </div>

</div>

@endsection