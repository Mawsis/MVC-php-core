<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Paginator;
use app\core\Request;
use app\core\Response;
use app\models\User;

class UserController extends Controller
{
    public function showUser(Request $request, Response $response, $id)
    {
        $user = User::with(['posts'])->where('id', '=', $id)->first();

        if (!$user) {
            return $response->json(['error' => 'User not found'], 404);
        }

        return $response->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'posts' => $user->posts,
            ]
        ]);
    }


    public function listUsers(Request $request, Response $response)
    {

        $users = Paginator::paginate(
            User::query()->orderBy('id', 'ASC'),
            $response,
            $request->getBody()['per_page'] ?? 2,
            $request->getBody()['page']
        );
        return $response->render('users', [
            'users' => $users
        ]);
    }
}