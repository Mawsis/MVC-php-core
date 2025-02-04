<?php
namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (Application::isGuest()) {
            throw new ForbiddenException;
        }
    }
}