<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Registro</title>
</head>
<body>
    <h1>¡Hola {{ $nombre }}!</h1>
    <p>Tu registro en Ecoaventura ha sido realizado con éxito. Bienvenido a nuestra comunidad.</p>
    <p>Para completar tu registro, por favor verifica tu correo haciendo clic en el siguiente enlace:</p>
    <p><a href="{{ url('/verify/'.$correo) }}" target="_blank">Verificar mi cuenta</a></p>
    <p>Si no has solicitado este registro, por favor ignora este correo.</p>
</body>
</html>
