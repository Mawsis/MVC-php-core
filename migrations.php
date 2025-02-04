<?php

use app\core\Application;
use Dotenv\Dotenv;

require_once __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'userClass' => app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_CONNECTION'] . ":host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];


$app = new Application(__DIR__, $config);


$app->db->applyMigrations();