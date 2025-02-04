<?php

namespace app\core;

use app\models\User;

class Auth
{
    private Session $session;
    public ?UserModel $user = null;

    public function __construct(Session $session, string $userClass)
    {
        $this->session = $session;

        // Retrieve authenticated user if session exists
        $userId = $this->session->get('user');
        if ($userId) {
            $this->user = $userClass::findOne(['id' => $userId]);
        }
    }

    public function login(UserModel $user): bool
    {
        $this->user = $user;
        $this->session->set('user', $user->id);
        return true;
    }

    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
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
