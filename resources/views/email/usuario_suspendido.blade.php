<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta suspendida</title>
</head>
<body>
    <h2>Hola {{ $usuario->persona->nombre }} {{ $usuario->persona->apellidos }}</h2>
    <p>Tu cuenta en <strong>Ecoaventura</strong> ha sido suspendida temporalmente.</p>
    <p><strong>No podrás iniciar sesión</strong> mientras dure la suspensión.</p>
    <p>Además, <strong>todos tus destinos y las rutas que los contenían han sido suspendidos automáticamente</strong>.</p>
    <p>Si en el futuro vuelves a ser habilitado, deberás reactivar manualmente cada destino desde el panel de administración.</p>
    <p>Si tienes dudas, contacta al administrador del sistema.</p>
    <hr>
    <p style="font-size: 12px; color: #666;">Ecoaventura - Turismo responsable</p>
</body>
</html>