<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\form\data\LoginFormData;
use app\form\data\RegisterFormData;
use app\models\User;
use app\requests\LoginRequest;
use app\requests\RegisterRequest;

class AuthController extends Controller
{
    public function loginIndex()
    {
        $loginForm = new LoginFormData;
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }
    public function loginStore(LoginRequest $request, Response $response)
    {
        $loginForm = new LoginFormData;
        if ($request->validate()) {
            $user = User::findOne(['email' => $request->validated()['email']]);
            if (password_verify($request->validated()['password'], $user->password)) {
                Application::$app->auth->login($user);
                Application::$app->session->setFlash("success", "Login Successful");
                $response->redirect('/');
                return;
            } else {
                $request->addError('password', 'Password doesn\'t match');
            }
        }
        $loginForm->loadData($request->getBody(), $request->errors);
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }
    public function registerIndex(Request $request)
    {
        $user = new RegisterFormData;

        return $this->render('register', [
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
            Application::$app->session->setFlash("success", "Register Successful");
            Application::$app->response->redirect("/");
            return $response->redirect('/');
        }
        $user = new RegisterFormData;
        $user->loadData($request->getBody(), $request->errors);
        return $this->render('register', [
            'model' => $user
        ]);
    }
    public function logout(Request $request, Response $response)
    {
        Application::$app->auth->logout();
        Application::$app->session->setFlash("danger", "Logged out");
        $response->redirect('/');
    }
    public function profile()
    {
        Application::$app->view->title = "Profile";
        return $this->render('profile');
    }
}