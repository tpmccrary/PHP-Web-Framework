<?php

use app\core\Application;

class m0001_initial
{
    public function up()
    {
        // reference databse in Application
        $database = Application::$app->database;

        // sql query to create users table
        $sql = "CREATE TABLE user (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            password VARCHAR(256) NOT NULL,
            type VARCHAR(30) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $database->pdo->exec($sql);

    }

    public function down()
    {
        // reference databse in Application
        $database = Application::$app->database;

        echo "Down migration";
        $sql = "DROP TABLE IF EXISTS `user`;";
        $database->pdo->exec($sql);
    }
}