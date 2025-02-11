<?php

namespace app\core;

use app\core\facades\Session;
use app\models\User;

class Auth
{
    public ?UserModel $user = null;

    public function __construct()
    {
        $userClass = Config::get('auth.userClass');
        // Retrieve authenticated user if session exists
        $userId = Session::get('user');
        if ($userId) {
            $this->user = $userClass::findOne(['id' => $userId]);
        }
    }

    public function login(UserModel $user): bool
    {
        $this->user = $user;
        Session::set('user', $user->id);
        return true;
    }

    public function logout(): void
    {
        $this->user = null;
        Session::remove('user');
    }

    public function user(): ?UserModel
    {
        return $this->user;
    }

    public function isGuest(): bool
    {
        return !$this->user;
    }
}