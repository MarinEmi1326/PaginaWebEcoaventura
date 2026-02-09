<!DOCTYPE html>
<html>
<head>
    <title>Actualización de solicitud - Ecoaventura</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2 style="color: #c53030;">Hola, {{ $nombre }}</h2>
    <p>Lamentamos informarte que tu solicitud para unirte a <strong>Ecoaventura</strong> ha sido rechazada en este momento.</p>
    
    <div style="background-color: #fff5f5; border-left: 4px solid #c53030; padding: 15px; margin: 20px 0;">
        <strong>Motivo del rechazo:</strong><br>
        {{ $motivo }}
    </div>

    <p>Si consideras que esto es un error o deseas realizar una nueva solicitud corrigiendo los puntos anteriores, no dudes en contactarnos.</p>

    <p>Atentamente,<br>El equipo de Ecoaventura</p>
</body>
</html>