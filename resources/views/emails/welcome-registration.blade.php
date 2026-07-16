<!DOCTYPE html>
<html>
<head>
    <title>Completar Registro</title>
</head>
<body>
    <h1>¡Hola, {{ $name }}!</h1>
    <p>Se ha iniciado un proceso de creación de cuenta para ti en nuestro sistema.</p>
    <p>Para definir tu contraseña y activar tu cuenta, haz clic en el siguiente enlace de registro temporal:</p>

    <p>
        <a href="{{ $registrationUrl }}" style="background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Completar mi registro
        </a>
    </p>

    <p style="color: gray;">Este enlace expirará en 24 horas.</p>
</body>
</html>
