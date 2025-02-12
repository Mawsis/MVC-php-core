<?php

return [
    'allowed_origins' => explode(',', $_ENV['CORS_ALLOW_ORIGIN'] ?? '*'),
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'allowed_headers' => ['Content-Type', 'Authorization'],
];