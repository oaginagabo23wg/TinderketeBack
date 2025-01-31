<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erabiltzailea sortua</title>
</head>
<body>
    <h1>Erabiltzailea ongi sortu da!</h1>
    <p>Kaixo {{ $user->name }}!</p>
    <p>Ongi etorri gure plataformara. Egin klik esteka honetan zure kontuan saioa hasteko:</p>
    <a href="{{ env('APP_URL') }}:8000/api/activate/{{ $user->id }}">
        Kontua aktibatu eta saioa hasi
    </a>
    <p>Eskerrik asko gure zerbitzuetan izena emateagatik!</p>
</body>
</html>
