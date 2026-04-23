<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta habilitada</title>
</head>
<body>
    <h2>Hola {{ $usuario->persona->nombre }} {{ $usuario->persona->apellidos }}</h2>
    <p>Tu cuenta en <strong>Ecoaventura</strong> ha sido <strong>habilitada nuevamente</strong>.</p>
    <p>Ya puedes iniciar sesión.</p>
    <p><strong>Importante:</strong> Tus destinos y rutas NO se han reactivado automáticamente. Deberás reactivar manualmente cada destino desde el panel de administración.</p>
    <p>Si tienes dudas, contacta al administrador del sistema.</p>
    <hr>
    <p style="font-size: 12px; color: #666;">Ecoaventura - Turismo responsable</p>
</body>
</html>