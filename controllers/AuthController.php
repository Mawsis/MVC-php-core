<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller{
    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }
    public function login(Request $request,Response $response) {
        $loginForm = new LoginForm;
        if($request->isPost()){
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                Application::$app->session->setFlash("success","Login Successful");
                $response->redirect('/');
                return;
            }
        }
        return $this->render('login',[
            'model'=>$loginForm
        ]);
    }
    public function register(Request $request) {
        $user = new User;
        if($request->isPost()){
            $user->loadData($request->getBody());
            if($user->validate() && $user->save()){
                Application::$app->session->setFlash("success","Register Successful");
                Application::$app->response->redirect("/");
            }
        }
        return $this->render('register',[
            'model'=>$user
        ]);
    }
    public function logout(Request $request,Response $response) {
        Application::$app->logout();
        Application::$app->session->setFlash("danger","Logged out");
        $response->redirect('/');
    }
    public function profile() {
        Application::$app->view->title = "Profile";
        return $this->render('profile');
    }
}