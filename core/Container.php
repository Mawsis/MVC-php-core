<?php

// src/Container.php
namespace app\core;

class Container
{
    protected static array $bindings = [];

    public static function bind(string $key, callable $resolver)
    {
        self::$bindings[$key] = $resolver;
    }

    public static function make(string $key)
    {
        if (!isset(self::$bindings[$key])) {
            throw new \Exception("Service {$key} is not bound in the container.");
        }

        return call_user_func(self::$bindings[$key]);
    }
}