<?php

use app\core\Application;
use app\core\Config;
use app\core\facades\DB;
use app\providers\AppServiceProvider;
use Dotenv\Dotenv;

// Start output buffering to prevent "headers already sent" issues
ob_start();

require_once __DIR__ . "/../vendor/autoload.php";

// Register shutdown function for fatal errors
// register_shutdown_function(function () {
//     $error = error_get_last();

//     if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
//         http_response_code(500);

//         // Clear any previous output
//         while (ob_get_level()) {
//             ob_end_clean();
//         }

//         // Pass error details to the fatal view
//         global $caughtError;
//         $caughtError = $error;
//         require_once __DIR__ . "/../views/errors/fatal.php";
//         exit;
//     }
// });

// // Handle Uncaught Exceptions
// set_exception_handler(function (Throwable $exception) {
//     http_response_code(500);
//     error_log($exception); // Log the error for debugging

//     // Clear any previous output
//     while (ob_get_level()) {
//         ob_end_clean();
//     }

//     // Pass exception details to the fatal view
//     global $caughtError;
//     $caughtError = [
//         'message' => $exception->getMessage(),
//         'file' => $exception->getFile(),
//         'line' => $exception->getLine(),
//         'trace' => $exception->getTraceAsString()
//     ];
//     require_once __DIR__ . "/../views/errors/fatal.php";
//     exit;
// });

$dotenv = Dotenv::createImmutable(dirname(__DIR__), null, false);
$dotenv->safeLoad();

Config::load(dirname(__DIR__) . '/config');


$app = new Application(dirname(__DIR__));

require_once __DIR__ . "/../routes/main.php";




$app->run();