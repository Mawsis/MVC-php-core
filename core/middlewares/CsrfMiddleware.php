<?php
namespace app\core\middlewares;

class CsrfMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['_csrf'] ?? '';
            if (!$csrfToken || $csrfToken !== $_SESSION['csrf_token']) {
                throw new \Exception('Invalid CSRF token', 403);
            }
        }
    }
}