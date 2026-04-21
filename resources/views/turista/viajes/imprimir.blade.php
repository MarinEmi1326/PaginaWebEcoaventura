<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - Ecoaventura</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: white;
            padding: 40px 20px;
            color: #1e2a22;
        }

        .comprobante {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e2ece9;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h1 {
            color: #1F6B4B;
            font-size: 24px;
            letter-spacing: 2px;
        }

        .logo p {
            color: #6c757d;
            font-size: 12px;
        }

        .badge-confirm {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            color: #1e2a22;
        }

        .separator {
            border-top: 1px solid #e2ece9;
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f2f0;
        }

        .info-label {
            font-weight: 600;
            color: #5B6B60;
            font-size: 13px;
        }

        .info-value {
            font-weight: 500;
            color: #1e2a22;
            font-size: 13px;
        }

        .total {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #1F6B4B;
            text-align: right;
        }

        .total .label {
            font-size: 14px;
            font-weight: 600;
        }

        .total .value {
            font-size: 22px;
            font-weight: 700;
            color: #1F6B4B;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2ece9;
            font-size: 11px;
            color: #adb5bd;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .comprobante {
                box-shadow: none;
                border: none;
                padding: 20px;
            }

            .btn-print {
                display: none;
            }
        }

        .btn-print {
            display: block;
            width: 200px;
            margin: 20px auto 0;
            padding: 10px;
            background-color: #1F6B4B;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
        }

        .btn-print:hover {
            background-color: #0F5A3A;
        }
    </style>
</head>

<body>
    <div class="comprobante">
        <div class="logo">
            <h1>ECOAVENTURA</h1>
            <p>Guía digital de Ocosingo, Chiapas</p>
        </div>

        <div style="text-align: center;">
            <span class="badge-confirm">✅ ¡Compra confirmada!</span>
        </div>

        <h2>Tu comprobante de pago está listo</h2>

        <div style="text-align: center; margin: 15px 0;">
            <div style="display: inline-block; background: #f8f9fa; padding: 8px 20px; border-radius: 10px;">
                <span style="font-size: 11px; color: #6c757d;">ID de Transacción</span><br>
                <strong style="font-size: 16px;">TXN-{{ str_pad($viaje->id_pago, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>
        </div>

        <div class="separator"></div>

        <div class="info-row">
            <span class="info-label">Paquete</span>
            <span class="info-value">{{ $viaje->paquete_nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Destino</span>
            <span class="info-value">{{ $viaje->destino_nombre }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Comprador</span>
            <span class="info-value">{{ auth()->user()->persona->nombre }}
                {{ auth()->user()->persona->apellidos }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Correo</span>
            <span class="info-value">{{ auth()->user()->correo }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de compra</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($viaje->fecha)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de visita</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($viaje->fecha_visita)->format('d/m/Y') }} a las
                {{ $viaje->horario }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Personas</span>
            <span class="info-value">{{ $viaje->personas }} personas</span>
        </div>
        <div class="info-row">
            <span class="info-label">Método de pago</span>
            <span class="info-value">Tarjeta de crédito/débito</span>
        </div>

        <div class="total">
            <span class="label">Total pagado</span>
            <div class="value">${{ number_format($viaje->monto, 2) }} MXN</div>
        </div>

        <div class="footer">
            <p>Este comprobante es válido como constancia de pago.</p>
            <p>Ecoaventura - Turismo responsable en la Selva Lacandona</p>
        </div>
    </div>

    <button class="btn-print" onclick="window.print();">
        🖨️ Imprimir comprobante
    </button>
</body>

</html>
