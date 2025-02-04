<?php
namespace app\core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger
{
    private static ?MonologLogger $logger = null;

    public static function getLogger(): LoggerInterface
    {
        if (self::$logger === null) {
            self::$logger = new MonologLogger('app');

            // Log file path (ensure 'logs/' directory exists)
            $logFilePath = __DIR__ . '/../logs/app.log';
            $handler = new StreamHandler($logFilePath, MonologLogger::DEBUG);

            // Attach handler to the logger
            self::$logger->pushHandler($handler);
        }

        return self::$logger;
    }
}