<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Paginator;
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
        $page = $request->getBody()['page'] ?? 1;
        $perPage = $request->getBody()['per_page'] ?? 5;

        $users = Paginator::paginate(User::query()->orderBy('id', 'ASC'), $response, $perPage, $page);
        return $response->render('users', [
            'users' => $users
        ]);
    }
}