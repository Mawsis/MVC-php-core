<?php

namespace app\models;

use app\core\Application;
use app\core\UserModel;

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public int $status = self::STATUS_INACTIVE;
    public ?int $id;
    public string $created_at;
    public array $posts;
    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->status = self::STATUS_INACTIVE;
        return parent::save();
    }
    public static function tableName(): string
    {
        return 'users';
    }
    public static function attributes(): array
    {
        return ['username', 'email', 'password', 'status'];
    }
    public static function primaryKey(): string
    {
        return "id";
    }
    public function getDisplayName(): string
    {
        return $this->username;
    }
    public function labels(): array
    {
        return [
            'confirmPassword' => "Password Confirmation",
            'email' => 'Email'
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class, "user_id");
    }
}