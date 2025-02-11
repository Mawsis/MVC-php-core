<?php

// src/Container.php
namespace app\core;

class Container
{
    protected static array $bindings = [];
    protected static array $singletons = [];

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
    public static function singleton(string $key, callable $resolver)
    {
        self::$singletons[$key] = null; // Placeholder
        self::$bindings[$key] = function () use ($key, $resolver) {
            if (self::$singletons[$key] === null) {
                self::$singletons[$key] = $resolver();
            }
            return self::$singletons[$key];
        };
    }
}