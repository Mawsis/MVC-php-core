<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;


class Controller
{
    public string $layout = 'main';
    public string $action = '';
    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }
    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }
}