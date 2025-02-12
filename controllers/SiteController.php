<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\facades\Auth;
use app\core\facades\Session;
use app\core\Request;
use app\core\Response;
use app\form\data\ContactFormData;
use app\requests\ContactRequest;

class SiteController extends Controller
{
    public function home(Request $request, Response $response)
    {
        $params =  ['name' => Auth::user()->username ?? 'Guest'];
        return $response->render('home', $params);
    }
    public function index(Request $request, Response $response)
    {
        $contact = new ContactFormData();
        return $response->render('contact', [
            'formData' => $contact
        ]);
    }

    public function store(ContactRequest $request, Response $response)
    {
        if ($request->validate()) {
            Session::setFlash("success", "Message sent Successfully");
            return $response->redirect('/');
        }
        $contact = new ContactFormData();
        $contact->loadData($request->getBody(), $request->errors);
        return $response->render('contact', [
            'formData' => $contact
        ]);
    }
}