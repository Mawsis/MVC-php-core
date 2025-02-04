<?php

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\controllers\UserController;
use app\core\Application;
use app\core\middlewares\AuthMiddleware;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(dirname(__DIR__), null, false);
$dotenv->safeLoad();

$config = [
    'userClass' => app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_CONNECTION'] . ":host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];


$app = new Application(dirname(__DIR__), $config);

require_once __DIR__ . "/../routes/main.php";

$app->run();
