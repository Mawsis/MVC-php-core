<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\facades\Auth;
use app\core\facades\Session;
use app\core\Request;
use app\core\Response;
use app\form\data\LoginFormData;
use app\form\data\RegisterFormData;
use app\models\User;
use app\requests\LoginRequest;
use app\requests\RegisterRequest;

class AuthController extends Controller
{
    public function loginIndex(Response $response)
    {
        $loginForm = new LoginFormData;
        return $response->render('login', [
            'model' => $loginForm
        ]);
    }
    public function loginStore(LoginRequest $request, Response $response)
    {
        $loginForm = new LoginFormData;
        if ($request->validate()) {
            $user = User::findOne(['email' => $request->validated()['email']]);
            if (password_verify($request->validated()['password'], $user->password)) {
                Auth::login($user);
                Session::setFlash("success", "Login Successful");
                $response->redirect('/');
                return;
            } else {
                $request->addError('password', 'Password doesn\'t match');
            }
        }
        $loginForm->loadData($request->getBody(), $request->errors);
        return $response->render('login', [
            'model' => $loginForm
        ]);
    }
    public function registerIndex(Request $request, Response $response)
    {
        $user = new RegisterFormData;

        return $response->render('register', [
            'model' => $user
        ]);
    }
    public function registerStore(RegisterRequest $request, Response $response)
    {
        if ($request->validate() && $request->validated()['password'] === $request->validated()['confirm_password']) {
            try {
                User::create([
                    'username' => $request->validated()['username'],
                    'email' => $request->validated()['email'],
                    'password' => password_hash($request->validated()['password'], PASSWORD_DEFAULT),
                    'status' => User::STATUS_INACTIVE
                ]);
            } catch (\Exception $e) {
                throw $e;
            }
            Session::setFlash("success", "Register Successful");
            $response->redirect("/");
            return $response->redirect('/');
        }
        $user = new RegisterFormData;
        $user->loadData($request->getBody(), $request->errors);
        return $response->render('register', [
            'model' => $user
        ]);
    }
    public function logout(Request $request, Response $response)
    {
        Auth::logout();
        Session::setFlash("danger", "Logged out");
        $response->redirect('/');
    }
    public function profile(Response $response)
    {
        return $response->render('profile');
    }
}