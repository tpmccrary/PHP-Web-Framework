<?php

// Require Application.php
require_once('./core/Application.php');

use app\core\Application;


$config = [
    'database' =>
    [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=tpmccrary_f21_db',
        'user' => 'root',
        'password' => 'password',
    ]
];

// Create new app and router
$app = new Application(__DIR__, $config);

$app->database->applyMigriation();
