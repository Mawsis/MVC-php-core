<?php

namespace app\core\helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\core\Config;

class JwtHelper
{
    public static function generateToken($user)
    {
        $payload = [
            'iss' => "your-app",
            'iat' => time(),
            'exp' => time() + Config::get('auth.jwt_expiration'),
            'sub' => $user->id
        ];

        return JWT::encode($payload, Config::get('auth.jwt_secret'), 'HS256');
    }

    public static function verifyToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(Config::get('auth.jwt_secret'), 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}