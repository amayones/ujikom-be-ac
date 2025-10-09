<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'allowed_origins' => [
        'https://ujikom.amayones.my.id',
        'http://localhost:5173',
        'http://localhost:3000'
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Origin'],
    'exposed_headers' => [],
    'max_age' => 86400,
    'supports_credentials' => true,
];