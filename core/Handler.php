<?php

namespace app\core;

use app\core\exceptions\ValidationException;
use app\core\exceptions\DatabaseException;
use app\core\exceptions\UnauthorizedException;
use app\core\exceptions\ForbiddenException;
use app\core\exceptions\NotFoundException;
use app\core\facades\Logger;
use Throwable;

class Handler
{
    public static function handle(Throwable $exception, Response $response)
    {
        $code = $exception->getCode() ?: 500;
        $message = $exception->getMessage();

        // Log the error
        Logger::error("Error: {$message} | Code: {$code}", ['exception' => $exception]);
        if (self::isApiRequest()) {
            return self::handleApiException($exception, $response);
        }

        return self::handleWebException($exception, $response);
    }

    private static function handleApiException(Throwable $exception, Response $response)
    {
        $code = $exception->getCode() ?: 500;
        $message = $exception->getMessage();

        if ($exception instanceof ValidationException) {
            return $response->json([
                'error' => true,
                'message' => $message,
                'errors' => $exception->getErrors(),
            ], 422);
        }

        return $response->json([
            'error' => true,
            'message' => $message,
        ], $code);
    }

    private static function handleWebException(Throwable $exception, Response $response)
    {
        $debug = $_ENV['APP_ENV'] === 'dev' || $_ENV['APP_ENV'] === 'local';
        return View::renderView('_error', [
            'exception' => $exception,
            'debug' => $debug ? $exception->getTraceAsString() : null,
        ], "error");
    }

    private static function isApiRequest(): bool
    {
        return strpos($_SERVER['REQUEST_URI'], '/api/') === 0 ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}