<?php

namespace app\core;

class Database
{
    // pdo
    public \PDO $pdo;
    // constructor
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function applyMigriation()
    {
        $this->createMigrationTables();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR. '/migrations');

        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration)
        {
            if ($migration === '.' || $migration === '..')
            {
                continue;
            }

            require_once Application::$ROOT_DIR. '/migrations/'. $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);

            $instance = new $className();

            $this->log("Appliying migration: ".$migration);

            $instance->up();

            $newMigrations[] = $migration;

        }

        if (!empty($newMigrations))
        {
            $this->saveAppliedMigrations($newMigrations);
        }
        else
        {
            $this->log('No new migrations to apply');
        }
    }

    public function createMigrationTables()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id int(11) NOT NULL AUTO_INCREMENT,
            migration varchar(255) NOT NULL,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    }

    public function getAppliedMigrations()
    {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();
        $migrations = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        return $migrations;
    }

    public function saveAppliedMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        // foreach ($migrations as $migration)
        // {
        //     $stmt->bindParam(':migration', $migration);
        //     $stmt->execute();
        // }
        $stmt->execute();
    }

    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

}