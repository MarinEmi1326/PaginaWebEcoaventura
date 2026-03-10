<!DOCTYPE html>
<html>
<head>
    <title>Solicitud en Espera</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2 style="color: #2c3e50;">¡Hola, {{ $nombre }}!</h2>
    <p>Hemos recibido tu solicitud para unirte a <strong>Ecoaventura</strong> como proveedor de servicios.</p>
    
    <p>Tu cuenta asociada al correo <strong>{{ $correo }}</strong> se encuentra actualmente en estado: <span style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px;">Pendiente de Revisión</span>.</p>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    
    <p><strong>¿Qué sigue ahora?</strong></p>
    <ul>
        <li>Nuestro equipo de administración revisará la información y documentos proporcionados.</li>
        <li>Recibirás un correo electrónico notificándote si tu solicitud fue <strong>Aprobada</strong> o <strong>Rechazada</strong>.</li>
    </ul>

    <p style="margin-top:20px;">
        <a href="{{ url('/verificar-correo/'.$token) }}"
        style="background:#16a34a;color:white;padding:10px 20px;text-decoration:none;border-radius:6px;">
            Confirmar correo electrónico
        </a>
    </p>

    <p>Gracias por tu paciencia.</p>
    <p>Atentamente,<br>El equipo de Ecoaventura</p>
</body>
</html>