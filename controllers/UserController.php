<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\User;

class UserController extends Controller
{
    public function showUser(Request $request, Response $response, $id)
    {
        $user = User::findOne(['id' => $id]);
        if (!$user) {
            $response->setStatusCode(404);
            return "User not found";
        }
        return "User found $user->username";
    }
}