<?php

namespace app\core\facades;

use app\core\Container;

class Logger extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'logger';
    }
}