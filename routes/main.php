<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\controllers\UserController;

$app->router->get("/", [SiteController::class, 'home']);

$app->router->get("/contact", [SiteController::class, 'index']);
$app->router->post("/contact", [SiteController::class, 'store']);

$app->router->get("/login", [AuthController::class, 'loginIndex']);
$app->router->post("/login", [AuthController::class, 'loginStore']);

$app->router->post("/register", [AuthController::class, 'registerStore']);
$app->router->get("/register", [AuthController::class, 'registerIndex']);

$app->router->get("/logout", [AuthController::class, 'logout']);

$app->router->get("/profile", [AuthController::class, 'profile']);

$app->router->get("/users/{id}", [UserController::class, 'showUser'], ["auth"]);
$app->router->get("/users", [UserController::class, 'listUsers'], ["auth"]);