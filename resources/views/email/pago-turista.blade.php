}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f4f7f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 560px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        }

        .header {
            background: #1F6B4B;
            padding: 32px 40px;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 22px;
        }

        .header p {
            color: #a8d5be;
            margin: 8px 0 0;
            font-size: 14px;
        }

        .body {
            padding: 32px 40px;
        }

        .check {
            text-align: center;
            margin-bottom: 24px;
        }

        .check-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: #e2ece9;
            border-radius: 50%;
            font-size: 32px;
        }

        h2 {
            color: #1a1a1a;
            font-size: 20px;
            margin: 0 0 8px;
        }

        p {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 16px;
        }

        .card {
            background: #f7f9f7;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 24px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e8ede9;
        }

        .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #888;
            font-size: 14px;
        }

        .value {
            color: #1a1a1a;
            font-size: 14px;
            font-weight: 600;
        }

        .total .value {
            color: #1F6B4B;
            font-size: 16px;
        }

        .highlight {
            background: #e2ece9;
            border-radius: 8px;
            padding: 16px 24px;
            margin: 16px 0;
        }

        .highlight p {
            margin: 0;
            color: #1F6B4B;
            font-size: 14px;
            font-weight: 600;
        }

        .footer {
            background: #f7f9f7;
            padding: 20px 40px;
            text-align: center;
        }

        .footer p {
            color: #aaa;
            font-size: 12px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🌿 Ecoaventura</h1>
            <p>Plataforma turística de Ocosingo, Chiapas</p>
        </div>
        <div class="body">
            <div class="check">
                <div class="check-circle">✅</div>
            </div>
            <h2>¡Tu pago fue confirmado!</h2>
            <p>Hola <strong>{{ $persona->nombre }}</strong>, tu reservación ha sido procesada exitosamente. Aquí está el
                resumen:</p>

            {{-- Detalles de la visita destacados --}}
            <div class="highlight">
                <p>📅 Fecha de visita: {{ \Carbon\Carbon::parse($pago->fecha_visita)->format('d/m/Y') }}</p>
                <p style="margin-top:8px;">🕐 Horario: {{ $pago->horario }}</p>
                <p style="margin-top:8px;">👥 Personas: {{ $pago->personas }}</p>
            </div>

            <div class="card">
                <div class="row">
                    <span class="label">Destino</span>
                    <span class="value">{{ $destino->nombre }}</span>
                </div>
                <div class="row">
                    <span class="label">Paquete</span>
                    <span class="value">{{ $paquete->nombre }}</span>
                </div>
                <div class="row">
                    <span class="label">ID de transacción</span>
                    <span class="value" style="font-size:12px;">{{ $pago->stripe_payment_intent }}</span>
                </div>
                <div class="row">
                    <span class="label">Fecha de pago</span>
                    <span class="value">{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="row total">
                    <span class="label">Total pagado</span>
                    <span class="value">${{ number_format($pago->monto, 2) }} MXN</span>
                </div>
            </div>

            <p>Si tienes alguna duda puedes contactar directamente con el destino.</p>
            @if ($destino->telefono)
                <p>📞 <strong>{{ $destino->telefono }}</strong></p>
            @endif
        </div>
        <div class="footer">
            <p>Este correo fue generado automáticamente por Ecoaventura. Por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>

</html>
