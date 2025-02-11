<?php

namespace app\core\facades;

use app\core\Container;

class Facade
{
    public static function __callStatic($name, $arguments)
    {
        return Container::make(static::getFacadeAccessor())->$name(...$arguments);
    }

    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }
}