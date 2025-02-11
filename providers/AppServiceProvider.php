<?php

namespace app\providers;

use app\core\Auth;
use app\core\Container;
use app\core\Database;
use app\core\Handler;
use app\core\Logger;
use app\core\ServiceProvider;
use app\core\Session;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Container::singleton('db', function () {
            return new Database();
        });

        Container::bind('auth', function () {
            return new Auth();
        });

        Container::singleton('logger', function () {
            return Logger::getLogger();
        });

        Container::singleton('session', function () {
            return new Session();
        });

        Container::singleton('handler', function () {
            return new Handler();
        });
    }
}