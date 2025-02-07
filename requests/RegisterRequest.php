<?php

namespace app\requests;

use app\core\Request;

class RegisterRequest extends Request
{
    public function isAuthorized(): bool
    {
        return true;
    }

    public array $rules = [
        "username" => ["required", "min:3", "max:20"],
        "email" => ["required", "email", "unique:users:email"],
        "password" => ["required", "min:8", "max:20"],
        "confirm_password" => ["required"]
    ];
}