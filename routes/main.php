<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\controllers\UserController;

$app->router->get("/", [SiteController::class, 'home']);
$app->router->get("/contact", [SiteController::class, 'contact']);
$app->router->post("/contact", [SiteController::class, 'contact']);
$app->router->post("/login", [AuthController::class, 'login'], ["csrf"]);
$app->router->post("/register", [AuthController::class, 'register']);
$app->router->get("/login", [AuthController::class, 'login']);
$app->router->get("/register", [AuthController::class, 'register']);
$app->router->get("/logout", [AuthController::class, 'logout']);
$app->router->get("/profile", [AuthController::class, 'profile']);
$app->router->get("/user/{id}", [UserController::class, 'showUser'], ["auth"]);
