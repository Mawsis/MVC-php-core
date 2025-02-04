<?php
namespace app\core\middlewares;

use app\core\Application;

class JsonMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
            header('Content-Type: application/json');
        }
    }
}