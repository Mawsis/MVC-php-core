<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\controllers\UserController;
use app\core\facades\Route;

Route::get("/", [SiteController::class, 'home']);

Route::get("/contact", [SiteController::class, 'index']);
Route::post("/contact", [SiteController::class, 'store']);

Route::get("/login", [AuthController::class, 'loginIndex']);
Route::post("/login", [AuthController::class, 'loginStore']);

Route::post("/register", [AuthController::class, 'registerStore']);
Route::get("/register", [AuthController::class, 'registerIndex']);

Route::get("/logout", [AuthController::class, 'logout']);

Route::get("/profile", [AuthController::class, 'profile']);

Route::get("/users/{id}", [UserController::class, 'showUser'], ["jwt"]);
Route::get("/users", [UserController::class, 'listUsers'], ["auth"]);