<?php

namespace app\core;

class Config
{
    private static array $config = [];

    /**
     * Load configuration files
     */
    public static function load(string $path)
    {
        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            self::$config[$key] = require $file;
        }
    }

    /**
     * Get a config value
     */
    public static function get(string $key, mixed $default = null)
    {
        $keys = explode('.', $key);
        $config = self::$config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }
}