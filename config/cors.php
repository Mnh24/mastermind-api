<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
    ],

    // Use this for explicit origins (Flutter Web local dev ports, etc.)
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:4200',
        'http://127.0.0.1:4200',
        'http://localhost:5173',
        'http://127.0.0.1:5173',
    ],

    // Alternatively, during dev you can use patterns (comment the list above):
    // 'allowed_origins_patterns' => ['#^http://(localhost|127\.0\.0\.1)(:\d+)?$#'],

    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],

    // If you’re using Sanctum cookie-based auth for a SPA:
    'supports_credentials' => true,

    // Leave the rest as defaults
    'exposed_headers' => [],
    'max_age' => 0,
];
