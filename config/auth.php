<?php

return [
    'userClass' => app\models\User::class,
    'jwt_secret' => $_ENV['JWT_SECRET'],
    'jwt_expiration' => 3600,
];