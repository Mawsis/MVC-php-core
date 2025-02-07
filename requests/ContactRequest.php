<?php

namespace app\requests;

use app\core\Request;

class ContactRequest extends Request
{
    public function isAuthorized(): bool
    {
        return true;
    }

    public array $rules = [
        "subject" => ["required", "min:10"],
        "email" => ["required", "email"],
        "body" => ["required"]
    ];
}