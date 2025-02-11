<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\ForbiddenException;
use app\core\exceptions\UnauthorizedException;
use app\core\facades\Auth;

class AuthMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (Auth::isGuest()) {
            throw new UnauthorizedException;
        }
    }
}