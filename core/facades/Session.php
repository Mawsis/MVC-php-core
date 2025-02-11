<?php

namespace app\core\facades;

use app\core\Container;

class Session extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'session';
    }
}