@extends('layouts.admin')

@section('title', 'Ventas')

@section('content')
    <div class="mb-4">
        <h1 class="ea-page-title mb-1">Ventas</h1>
        <p class="ea-subtitle mb-0">Resumen de ventas de tus destinos.</p>
    </div>

    {{-- Cards resumen --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="ea-card p-4 text-center h-100">
                <div class="mb-2" style="color: #3b82f6;">
                    <i class="bi bi-cart fs-1"></i>
                </div>
                <div class="ea-summary-number">{{ $ventasTotales }}</div>
                <div class="ea-summary-label">Ventas totales</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="ea-card p-4 text-center h-100">
                <div class="mb-2" style="color: #10b981;">
                    <i class="bi bi-currency-dollar fs-1"></i>
                </div>
                <div class="ea-summary-number">${{ number_format($ingresosTotales, 2) }}</div>
                <div class="ea-summary-label">Ingresos</div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="ea-card p-4 text-center h-100">
                <div class="mb-2" style="color: #f59e0b;">
                    <i class="bi bi-ticket-perforated fs-1"></i>
                </div>
                <div class="ea-summary-number">${{ number_format($ticketPromedio, 2) }}</div>
                <div class="ea-summary-label">Ticket promedio</div>
            </div>
        </div>
    </div>

    {{-- Tabla de ventas --}}
    <div class="ea-card p-0 overflow-hidden">
        <div class="p-4 border-bottom" style="border-color: var(--ea-line) !important; background: rgba(255,255,255,.18);">
            <h5 class="ea-report-title mb-0">Historial de Ventas</h5>
        </div>

        <div class="table-responsive">
            <table class="table ea-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Paquete</th>
                        <th>Destino</th>
                        <th>Fecha</th>
                        <th>Precio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                        <tr>
                            <td>
                                <div class="fw-semibold small">{{ $venta->persona_nombre }} {{ $venta->persona_apellidos }}
                                </div>
                            </td>
                            <td>{{ $venta->paquete_nombre }}</td>
                            <td>{{ $venta->destino_nombre }}</td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-success">${{ number_format($venta->monto, 2) }}</td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1"
                                    style="background-color: #d1fae5; color: #059669;">
                                    Confirmado
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
