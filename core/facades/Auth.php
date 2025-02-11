<?php

namespace app\core\facades;

use app\core\Container;

class Auth extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'auth';
    }
}