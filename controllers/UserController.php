<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\User;
use app\resources\UserResource;

class UserController extends Controller
{
    public function showUser(Request $request, Response $response, $id)
    {
        $user = User::query()->where('id', "=", $id)->first();
        if (!$user) {
            return $response->json(['error' => 'User not found'], 404);
        }

        return $response->json(UserResource::make($user));
    }

    public function listUsers(Request $request, Response $response)
    {
        $users = User::query()->get();
        return $response->json(UserResource::collection($users));
    }
}