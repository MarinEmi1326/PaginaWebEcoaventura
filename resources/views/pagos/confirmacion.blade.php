@extends('layouts.app')

@section('content')
    <div class="container py-5" style="max-width:520px;">
        <div class="ea-card p-0 overflow-hidden text-center">
            <div class="p-5">
                <div class="mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:80px;height:80px;background:#e2ece9;">
                        <i class="bi bi-check-lg text-success" style="font-size:2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-1">¡Pago exitoso!</h3>
                    <p class="text-muted">Tu reservación ha sido confirmada.</p>
                </div>

                <div class="rounded-3 p-4 text-start mb-4" style="background:#f1f5f2;">
                    <div class="mb-2">
                        <span class="text-muted small">Destino</span>
                        <p class="fw-semibold mb-0">{{ $destino->nombre }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Paquete</span>
                        <p class="fw-semibold mb-0">{{ $paquete->nombre }}</p>
                    </div>
                    @php
                        $pago = DB::table('pago')
                            ->where('id_paquete', $paquete->id_paquete)
                            ->where(
                                'id_persona',
                                auth()->user()
                                    ? DB::table('persona')
                                        ->where('id_usuario', auth()->id())
                                        ->value('id_persona')
                                    : null,
                            )
                            ->orderByDesc('fecha')
                            ->first();
                    @endphp
                    @if ($pago)
                        <div class="mb-2">
                            <span class="text-muted small">Fecha de visita</span>
                            <p class="fw-semibold mb-0">
                                <i class="bi bi-calendar3 me-1 text-success"></i>
                                {{ \Carbon\Carbon::parse($pago->fecha_visita)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small">Horario</span>
                            <p class="fw-semibold mb-0">
                                <i class="bi bi-clock me-1 text-success"></i>
                                {{ $pago->horario }}
                            </p>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small">Personas</span>
                            <p class="fw-semibold mb-0">
                                <i class="bi bi-people me-1 text-success"></i>
                                {{ $pago->personas }}
                            </p>
                        </div>
                    @endif
                    <div>
                        <span class="text-muted small">Total pagado</span>
                        <p class="fw-bold text-success mb-0">${{ number_format($paquete->precio, 2) }} MXN</p>
                    </div>
                </div>

                <p class="text-muted small mb-4">
                    <i class="bi bi-envelope me-1"></i>
                    Se ha enviado una confirmación a tu correo electrónico.
                </p>

                <a href="{{ route('destinos.index') }}" class="btn btn-success rounded-3 px-4 py-2 w-100">
                    <i class="bi bi-compass me-2"></i>Explorar más destinos
                </a>
            </div>
        </div>
    </div>
@endsection
