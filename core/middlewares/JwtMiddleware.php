<?php

namespace app\core\middlewares;

use app\core\helpers\JwtHelper;
use app\core\Request;
use app\core\Response;
use app\models\User;

class JwtMiddleware extends BaseMiddleware
{
    public function execute()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            throw new \app\core\exceptions\UnauthorizedException();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $decoded = JwtHelper::verifyToken($token);

        if (!$decoded) {
            throw new \app\core\exceptions\UnauthorizedException();
        }

        // Attach user to request
        $user = User::findOne(['id' => $decoded->sub]);
        if (!$user) {
            throw new \app\core\exceptions\UnauthorizedException();
        }

        $_SESSION['user'] = $user->id;
    }
}