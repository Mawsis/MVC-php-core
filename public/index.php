<?php

use app\core\Application;
use Dotenv\Dotenv;

// Start output buffering to prevent "headers already sent" issues
ob_start();

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(dirname(__DIR__), null, false);
$dotenv->safeLoad();

$config = [
    'userClass' => app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_CONNECTION'] . ":host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(dirname(__DIR__), $config);

require_once __DIR__ . "/../routes/main.php";

// Register shutdown function for fatal errors
register_shutdown_function(function () {
    $error = error_get_last();

    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);

        // Clear any previous output
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Pass error details to the fatal view
        global $caughtError;
        $caughtError = $error;
        require_once __DIR__ . "/../views/errors/fatal.php";
        exit;
    }
});

// Handle Uncaught Exceptions
set_exception_handler(function (Throwable $exception) {
    http_response_code(500);
    error_log($exception); // Log the error for debugging

    // Clear any previous output
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Pass exception details to the fatal view
    global $caughtError;
    $caughtError = [
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ];
    require_once __DIR__ . "/../views/errors/fatal.php";
    exit;
});



$app->run();