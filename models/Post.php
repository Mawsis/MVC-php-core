<?php

namespace app\models;

use app\core\DbModel;

class Post extends DbModel
{
    public int $id;
    public string $title;
    public string $body;
    public int $user_id;
    public string $created_at;

    public static function tableName(): string
    {
        return 'posts';
    }

    public static function attributes(): array
    {
        return ['id', 'title', 'body', 'user_id'];
    }

    public static function primaryKey(): string
    {
        return "id";
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}