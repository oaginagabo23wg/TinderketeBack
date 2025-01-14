<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['name'] }} -ren Mezu berria</title>
</head>
<body>
    <h1>{{ $data['name'] }} -ren Mezu berria</h1>
    <p><strong>Izena:</strong> {{ $data['name'] ?? 'Usuario desconocido' }}</p>
    <p><strong>Email-a:</strong> {{ $data['email'] }}</p>
    <p><strong>Mezua:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>
