<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\User;

class UserController extends Controller
{
    public function showUser(Request $request, Response $response, $id)
    {
        $user = User::query()->where('id', "=", $id)->where("username", '=', 'Mawsis')->first();
        if (!$user) {
            $response->setStatusCode(404);
            return "User not found";
        }
        return $response->json($user);
    }
}