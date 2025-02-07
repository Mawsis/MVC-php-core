<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\requests\ContactRequest;

class SiteController extends Controller
{
    public function home(Request $request, Response $response)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);
        $params =  ['name' => Application::$app->auth->user->username ?? 'Guest'];
        return $this->render('home', $params);
    }
    public function index(Request $request, Response $response)
    {
        $contact = new \app\form\data\ContactFormData();
        return $this->render('contact', [
            'formData' => $contact
        ]);
    }

    public function store(ContactRequest $request, Response $response)
    {
        dump($request->validated());
        return;
        if ($request->validate()) {
            Application::$app->session->setFlash("success", "Message sent Successfully");
            return $response->redirect('/');
        }
    }
}