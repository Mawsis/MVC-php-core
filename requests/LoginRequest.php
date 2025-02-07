<?php

namespace app\requests;

use app\core\Request;

class LoginRequest extends Request
{
    public function isAuthorized(): bool
    {
        return true;
    }

    public array $rules = [
        "email" => ["exists:users:email", "email", "required"],
        "password" => ["required"]
    ];
}