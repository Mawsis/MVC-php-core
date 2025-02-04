<?php

return [
    'json' => app\core\middlewares\JsonMiddleware::class,
    'auth' => app\core\middlewares\AuthMiddleware::class,
    'csrf' => app\core\middlewares\CsrfMiddleware::class,
];