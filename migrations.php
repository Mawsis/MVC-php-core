<?php

use app\core\Application;
use app\core\Config;
use app\core\Container;
use app\core\Database;
use app\core\facades\DB;
use Dotenv\Dotenv;

require_once __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Config::load(__DIR__ . "/config");

$app = new Application(rootPath: __DIR__);
Container::singleton('db', function () {
    return new Database();
});

$command = $argv[1] ?? null;

switch ($command) {
    case 'migrate':
        DB::applyMigrations();
        break;
    case 'rollback':
        DB::rollbackMigrations();
        break;
    default:
        echo "Available commands: \n";
        echo "  php migrations.php migrate    # Apply migrations\n";
        echo "  php migrations.php rollback   # Rollback last batch\n";
        break;
}