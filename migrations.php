<?php

use app\core\Application;
use app\core\Config;
use app\core\Container;
use app\core\Database;
use app\core\facades\DB;
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

Config::load(__DIR__ . "/config");

$app = new Application(rootPath: __DIR__);
Container::singleton('db', function () {
    return new Database();
});

DB::applyMigrations();