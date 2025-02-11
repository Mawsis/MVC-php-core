<?php

namespace app\core\facades;

use app\core\Container;

class Handler extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'handler';
    }
}