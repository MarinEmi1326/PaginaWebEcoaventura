@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width:560px;">
    <a href="{{ route('destinos.show', $destino->id_destino) }}"
        class="text-decoration-none fw-semibold text-success d-inline-flex align-items-center gap-1 mb-4">
        <i class="bi bi-arrow-left"></i> Regresar al destino
    </a>

    <div class="ea-card p-0 overflow-hidden">
        <div class="p-4 border-bottom" style="background:rgba(255,255,255,.25);">
            <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $destino->nombre }}</p>
            <h4 class="fw-bold mb-0">🎒 {{ $paquete->nombre }}</h4>
            @if ($paquete->descripcion)
                <p class="text-muted small mt-2 mb-0">{{ $paquete->descripcion }}</p>
            @endif
        </div>

        <div class="p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Total a pagar</span>
                <span class="fw-bold fs-4 text-success">${{ number_format($paquete->precio, 2) }} MXN</span>
            </div>
        </div>

        <div class="p-4">
            @if (session('error'))
                <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
            @endif

            <form id="forma-pago" action="{{ route('pagos.procesar', $paquete->id_paquete) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method_id" id="payment_method_id">

                {{-- Fecha de visita --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small">
                        <i class="bi bi-calendar3 me-1 text-success"></i>Fecha de visita
                    </label>
                    <input type="date" name="fecha_visita" id="fecha_visita"
                        class="form-control rounded-3 py-2 @error('fecha_visita') is-invalid @enderror"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('fecha_visita') }}" required>
                    @error('fecha_visita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Número de personas con rango --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small">
                        <i class="bi bi-people me-1 text-success"></i>Número de personas
                    </label>
                    <input type="number" name="personas" id="personas"
                        class="form-control rounded-3 py-2 @error('personas') is-invalid @enderror"
                        min="{{ $rango['min'] }}" 
                        @if($rango['max'] > 0) max="{{ $rango['max'] }}" @endif
                        value="{{ old('personas', $rango['min']) }}" required>
                    @if($rango['min'] > 0 && $rango['max'] > 0)
                        <div class="form-text small mt-1 text-muted">
                            <i class="bi bi-info-circle"></i> Este paquete acepta <strong>{{ $rango['min'] }} a {{ $rango['max'] }} personas</strong>.
                        </div>
                    @elseif($rango['min'] > 0)
                        <div class="form-text small mt-1 text-muted">
                            <i class="bi bi-info-circle"></i> Mínimo <strong>{{ $rango['min'] }}</strong> persona(s).
                        </div>
                    @endif
                    @error('personas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Horario --}}
                <div class="mb-4">
                    <label class="form-label fw-bold small">
                        <i class="bi bi-clock me-1 text-success"></i>Horario preferido
                    </label>
                    <select name="horario" id="horario_select" class="form-select rounded-3 py-2 @error('horario') is-invalid @enderror" required>
                        <option value="" disabled {{ old('horario') ? '' : 'selected' }}>Selecciona un horario</option>
                        <option value="08:00" {{ old('horario') == '08:00' ? 'selected' : '' }}>8:00 AM</option>
                        <option value="09:00" {{ old('horario') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                        <option value="10:00" {{ old('horario') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                        <option value="11:00" {{ old('horario') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                        <option value="12:00" {{ old('horario') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                        <option value="13:00" {{ old('horario') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                        <option value="14:00" {{ old('horario') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                        <option value="15:00" {{ old('horario') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                        <option value="16:00" {{ old('horario') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                    </select>
                    <div id="horario-aviso" class="small text-muted mt-1"></div>
                    @error('horario')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Datos tarjeta --}}
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
                    <i class="bi bi-shield-lock me-1 text-success"></i> Pago seguro procesado por Stripe. Tus datos están cifrados.
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
    const nombreTarjeta = document.getElementById('nombre-tarjeta');
nombreTarjeta.addEventListener('input', function() {
    // Permite letras (incluyendo acentos, eñes), espacios, puntos, guiones
    this.value = this.value.replace(/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s\.\-]/g, '');
});
    // Validación de horarios ocupados
    const horariosOcupados = @json($horariosOcupados ?? []);
    function actualizarHorarios() {
        const fecha = document.getElementById('fecha_visita').value;
        const select = document.getElementById('horario_select');
        const aviso = document.getElementById('horario-aviso');
        if (!fecha) {
            aviso.innerHTML = '';
            return;
        }
        const ocupados = horariosOcupados[fecha] || [];
        let opcionSeleccionada = false;
        Array.from(select.options).forEach(option => {
            if (option.value === '') return;
            if (ocupados.includes(option.value)) {
                option.disabled = true;
                option.style.backgroundColor = '#f8f9fa';
                option.style.color = '#adb5bd';
            } else {
                option.disabled = false;
                option.style.backgroundColor = '';
                option.style.color = '';
                if (!opcionSeleccionada && !select.value) {
                    option.selected = true;
                    opcionSeleccionada = true;
                }
            }
        });
        if (ocupados.length > 0) {
            aviso.innerHTML = '<i class="bi bi-info-circle me-1"></i> Horarios ocupados: ' + ocupados.join(', ');
            aviso.style.color = '#dc3545';
        } else {
            aviso.innerHTML = '<i class="bi bi-check-circle me-1"></i> Hay disponibilidad para esta fecha';
            aviso.style.color = '#198754';
        }
    }
    document.getElementById('fecha_visita').addEventListener('change', actualizarHorarios);
    if (document.getElementById('fecha_visita').value) actualizarHorarios();

    // Stripe (inicialización correcta)
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const style = {
            base: { fontSize: '16px', color: '#32325d', '::placeholder': { color: '#aab7c4' } },
            invalid: { color: '#dc3545' }
        };
        const cardElement = elements.create('card', { style, hidePostalCode: true });
        cardElement.mount('#card-element');
        cardElement.on('change', ({ error }) => {
            document.getElementById('card-errors').textContent = error ? error.message : '';
        });
        const form = document.getElementById('forma-pago');
        const btn = document.getElementById('btn-pagar');
        const nombreTarjeta = document.getElementById('nombre-tarjeta');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!nombreTarjeta.value.trim()) {
                document.getElementById('card-errors').textContent = 'Ingresa el nombre en la tarjeta';
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: { name: nombreTarjeta.value.trim() }
            });
            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-lock me-2"></i>Pagar ${{ number_format($paquete->precio, 2) }} MXN';
            } else {
                document.getElementById('payment_method_id').value = paymentMethod.id;
                form.submit();
            }
        });
    });
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/destinos.css') }}">
@endpush