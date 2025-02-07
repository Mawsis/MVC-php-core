<?php

namespace app\form\data;

use app\core\FormData;

class RegisterFormData extends FormData
{
    protected array $labels = [
        'username' => 'Your Username',
        'email' => 'Your Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password'
    ];
}