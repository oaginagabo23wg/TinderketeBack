<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body>
    <h1>Nuevo mensaje de contacto</h1>
    <p><strong>Nombre:</strong> {{ $data['name'] ?? 'Usuario desconocido' }}</p>
    <p><strong>Correo electrónico:</strong> {{ $data['email'] }}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>
