<?php

namespace app\core\exceptions;

use app\core\Application;
use app\core\Response;
use app\core\View;
use Psr\Log\LoggerInterface;
use Throwable;

class Handler
{
    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = Application::$app->logger;
    }

    public function handle(Throwable $exception)
    {
        $code = $exception->getCode() ?: 500;
        Application::$app->response->setStatusCode($code);

        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
        ]);

        if ($this->isApiRequest()) {
            echo json_encode([
                'error' => true,
                'message' => $exception->getMessage(),
            ]);
        } else {
            echo View::renderView('_error', ['exception' => $exception]);
        }
    }

    private function isApiRequest(): bool
    {
        return strpos($_SERVER['REQUEST_URI'], '/api/') === 0 || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}