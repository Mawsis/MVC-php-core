<?php

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\controllers\UserController;
use app\core\Application;
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


$app->router->get("/", [SiteController::class, 'home']);
$app->router->get("/contact", [SiteController::class, 'contact']);
$app->router->post("/contact", [SiteController::class, 'contact']);
$app->router->post("/login", [AuthController::class, 'login']);
$app->router->post("/register", [AuthController::class, 'register']);
$app->router->get("/login", [AuthController::class, 'login']);
$app->router->get("/register", [AuthController::class, 'register']);
$app->router->get("/logout", [AuthController::class, 'logout']);
$app->router->get("/profile", [AuthController::class, 'profile']);
$app->router->get("/user/{id}", [UserController::class, 'showUser']);

$app->run();