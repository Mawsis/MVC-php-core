<?php

namespace app\core\facades;

use app\core\Container;

class Route extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'route';
    }
}