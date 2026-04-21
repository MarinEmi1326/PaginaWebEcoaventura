@extends('layouts.app')

@section('content')
    <div class="container py-5" style="max-width: 800px;">
        <div class="mb-4">
            <a href="{{ route('turista.viajes') }}" class="text-decoration-none text-success">
                <i class="bi bi-arrow-left me-1"></i> Volver a mis viajes
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <span class="ea-chip green fs-6 px-4 py-2">¡Compra confirmada!</span>
                    </div>
                    <h3 class="fw-bold">Tu comprobante de pago está listo</h3>
                </div>

                <div class="border-top pt-4 mb-4">
                    <div class="row g-3">
                        <div class="col-12 text-center">
                            <h4 class="fw-bold text-success">ECOAVENTURA</h4>
                            <p class="text-muted">Comprobante de Compra</p>
                        </div>
                        <div class="col-12 text-center">
                            <div class="ea-card d-inline-block p-3">
                                <small class="text-muted">ID de Transacción</small>
                                <div class="fw-bold">TXN-{{ str_pad($viaje->id_pago, 6, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Paquete</div>
                            <div class="fw-semibold">{{ $viaje->paquete_nombre }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Destino</div>
                            <div class="fw-semibold">{{ $viaje->destino_nombre }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Comprador</div>
                            <div class="fw-semibold">{{ auth()->user()->persona->nombre }}
                                {{ auth()->user()->persona->apellidos }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Correo</div>
                            <div class="fw-semibold">{{ auth()->user()->correo }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Fecha de compra</div>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($viaje->fecha)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Método de pago</div>
                            <div class="fw-semibold">Tarjeta de crédito/débito</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Referencia</div>
                            <div class="fw-semibold">{{ $viaje->stripe_payment_intent }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Total pagado</div>
                            <div class="fw-bold fs-4 text-success">${{ number_format($viaje->monto, 2) }} MXN</div>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4 mt-4 text-center">
                    <a href="{{ route('turista.viajes.imprimir', $viaje->id_pago) }}" target="_blank"
                        class="btn btn-outline-success rounded-3 px-4">
                        <i class="bi bi-printer me-2"></i> Imprimir comprobante
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
