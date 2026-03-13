@extends('layouts.admin')

@section('title', 'Gestión de Destinos')

@section('content')

    <div class="mb-4">
        <h1 class="ea-page-title mb-1">Gestión de Destinos</h1>
    </div>

    <div class="ea-card p-0 overflow-hidden">

        {{-- Barra superior --}}
        <div class="p-3 p-md-4 border-bottom d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3"
            style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.15);">

            <div></div>

            <div class="d-flex flex-column flex-md-row gap-2">
                <div style="min-width: 240px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 rounded-end-3"
                            placeholder="Buscar destino...">
                    </div>
                </div>

                <select class="form-select rounded-3" style="min-width: 120px;">
                    <option>Todas</option>
                    <option>Activos</option>
                    <option>Suspendidos</option>
                </select>

                <select class="form-select rounded-3" style="min-width: 120px;">
                    <option>Todos</option>
                    <option>Arqueología</option>
                    <option>Naturaleza</option>
                    <option>Aventura</option>
                    <option>Cultura</option>
                    <option>Ecoturismo</option>
                </select>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table ea-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Destino</th>
                        <th>Categoría</th>
                        <th>Reportes</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                @forelse($destinos as $d)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $d->nombre }}</div>
                            <div class="small" style="color: var(--ea-muted);">Ocosingo, Chiapas</div>
                        </td>
                        <td>{{ $d->categorias ?? '—' }}</td>
                        <td>
                            <span class="ea-chip {{ $d->reportes_pendientes > 0 ? 'red' : 'gray' }}">
                                <i class="bi bi-flag me-1"></i>{{ $d->total_reportes }}
                            </span>
                        </td>
                        <td>
                            <span class="ea-chip {{ $d->activo === 'activo' ? 'green' : 'red' }}">
                                {{ $d->activo === 'activo' ? 'Activo' : 'Suspendido' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex align-items-center gap-3">
                                <a href="{{ route('admin.reportes.showDestino', $d->id_destino) }}"
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
                        <td colspan="5" class="text-center text-muted py-4">No hay destinos registrados.</td>
                    </tr>
                @endforelse
            </table>
        </div>

        {{-- Footer --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-3 px-md-4 py-3 border-top"
            style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.08);">
            <div class="small" style="color: var(--ea-muted);">
                Mostrando 1–10 de 28
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

    </div>

@endsection
