<?php

namespace app\core\facades;

use app\core\Container;

class DB extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'db';
    }
}