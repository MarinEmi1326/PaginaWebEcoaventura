@extends('layouts.app')

@section('content')
    <div class="container py-5" style="max-width:560px;">

        <a href="{{ route('destinos.show', $destino->id_destino) }}"
            class="text-decoration-none fw-semibold text-success d-inline-flex align-items-center gap-1 mb-4">
            <i class="bi bi-arrow-left"></i> Regresar al destino
        </a>

        <div class="ea-card p-0 overflow-hidden">

            {{-- Encabezado --}}
            <div class="p-4 border-bottom" style="background:rgba(255,255,255,.25);">
                <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $destino->nombre }}</p>
                <h4 class="fw-bold mb-0">🎒 {{ $paquete->nombre }}</h4>
                @if ($paquete->descripcion)
                    <p class="text-muted small mt-2 mb-0">{{ $paquete->descripcion }}</p>
                @endif
            </div>

            {{-- Resumen --}}
            <div class="p-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Total a pagar</span>
                    <span class="fw-bold fs-4 text-success">${{ number_format($paquete->precio, 2) }} MXN</span>
                </div>
                @if ($paquete->minimo_personas)
                    <p class="text-muted small mt-1 mb-0">
                        <i class="bi bi-people me-1"></i>Mínimo {{ $paquete->minimo_personas }} personas
                    </p>
                @endif
            </div>

            {{-- Formulario de pago --}}
            <div class="p-4">

                @if (session('error'))
                    <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
                @endif

                <form id="forma-pago" action="{{ route('pagos.procesar', $paquete->id_paquete) }}" method="POST">
                    @csrf

                    <input type="hidden" name="payment_method_id" id="payment_method_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nombre en la tarjeta</label>
                        <input type="text" id="nombre-tarjeta" class="form-control rounded-3 py-2"
                            placeholder="Como aparece en tu tarjeta" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Datos de la tarjeta</label>
                        <div id="card-element" class="form-control rounded-3 py-3" style="min-height:46px;"></div>
                        <div id="card-errors" class="text-danger small mt-1"></div>
                    </div>

                    <div class="rounded-3 p-3 mb-4 small" style="background:#f1f5f2;">
                        <i class="bi bi-shield-lock me-1 text-success"></i>
                        Pago seguro procesado por Stripe. Tus datos están cifrados.
                        <br>

                    </div>

                    <button type="submit" id="btn-pagar" class="btn btn-success w-100 rounded-3 py-2 fw-semibold">
                        <i class="bi bi-lock me-2"></i>Pagar ${{ number_format($paquete->precio, 2) }} MXN
                    </button>

                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements({
            appearance: {
                theme: 'stripe'
            },
            loader: 'auto'
        });

        const style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#dc3545'
            }
        };

        const cardElement = elements.create('card', {
            style,
            hidePostalCode: true,
            disableLink: true
        });
        cardElement.mount('#card-element');

        cardElement.on('change', ({
            error
        }) => {
            document.getElementById('card-errors').textContent = error ? error.message : '';
        });

        document.getElementById('forma-pago').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-pagar');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: document.getElementById('nombre-tarjeta').value
                }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                btn.disabled = false;
                btn.innerHTML =
                    '<i class="bi bi-lock me-2"></i>Pagar ${{ number_format($paquete->precio, 2) }} MXN';
            } else {
                document.getElementById('payment_method_id').value = paymentMethod.id;
                e.target.submit();
            }
        });
    </script>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
@endpush
