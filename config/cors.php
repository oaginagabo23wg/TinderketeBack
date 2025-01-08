<?php

return [
    'paths' => ['api/*'],  // Aplica CORS a rutas específicas, como la API
    'allowed_methods' => ['*'],  // Permitir todos los métodos HTTP (GET, POST, etc.)
    'allowed_origins' => ['*'],  // Permitir cualquier origen (puedes restringirlo si lo prefieres)
    'allowed_origins_patterns' => [],  // Patrón de orígenes permitidos (opcional)
    'allowed_headers' => ['*'],  // Permitir cualquier encabezado
    'exposed_headers' => [],  // Encabezados que se expondrán a las solicitudes
    'max_age' => 0,  // Tiempo de caché de la solicitud preflight (opcional)
    'supports_credentials' => false,  // Configura si se permiten cookies o credenciales
];
