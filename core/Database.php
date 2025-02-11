<?php

namespace app\core;

use app\core\exceptions\DatabaseException;
use app\core\facades\Logger;
use PDO;

class Database
{
    public PDO $pdo;
    public function __construct()
    {
        $config = Config::get('database');
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function applyMigrations()
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require_once Application::$ROOT_DIR . "/migrations/$migration";
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;
            Logger::info("Applying migration $migration");
            $instance->up();
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            Logger::info("All migrations applied");
        }
    }
    public function createMigrationTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }
    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    public function saveMigrations(array $migrations)
    {
        $migrations = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $migrations");
        $statement->execute();
    }
    public function rollbackMigrations()
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();

        if (empty($appliedMigrations)) {
            Logger::info("No migrations to rollback.");
            return;
        }

        $lastMigration = end($appliedMigrations);
        require_once Application::$ROOT_DIR . "/migrations/$lastMigration";
        $className = pathinfo($lastMigration, PATHINFO_FILENAME);
        $instance = new $className;

        Logger::info("Rolling back migration $lastMigration");
        $instance->down();

        $this->removeMigration($lastMigration);
    }
    private function removeMigration($migration)
    {
        $statement = $this->pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
        $statement->bindValue(":migration", $migration);
        $statement->execute();
    }

    public function prepare(string $sql)
    {
        try {
            Logger::info("Preparing SQL: $sql");
            return $this->pdo->prepare($sql);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function execute($sql)
    {
        try {
            Logger::info("Executing SQL: $sql");
            return $this->pdo->exec($sql);
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}