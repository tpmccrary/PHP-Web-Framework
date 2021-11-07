<?php

// Require Application.php
require_once('../core/Application.php');

use app\core\Application;

require_once('../controllers/ViewController.php');

use app\controllers\ViewController;

require_once("../controllers/AuthController.php");

use app\controllers\AuthController;

$config = [
    'database' =>
    [
        'dsn' => 'mysql:host=localhost;port=3306;dbname=tpmccrary_f21_db',
        'user' => 'root',
        'password' => 'password',
    ],
    'userClass' => \app\models\User::class,
];

// Create new app and router
$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [ViewController::class, 'mainpage']);
$app->router->get('/about', [ViewController::class, 'about']);

$app->router->get('/sign-in', [AuthController::class, 'signin']);
// post request singup
$app->router->post('/sign-in', [AuthController::class, 'signin']);
// get request sign up
$app->router->get('/sign-up', [AuthController::class, 'signup']);
// post request sign up
$app->router->post('/sign-up', [AuthController::class, 'signup']);

$app->router->get('/sign-out', [AuthController::class, 'signout']);

$app->router->get('/user-page', [AuthController::class, 'userpage']);
$app->router->post('/user-page', [AuthController::class, 'userpage']);

$app->router->get('/admin-page', [AuthController::class, 'adminpage']);
$app->router->post('/admin-page', [AuthController::class, 'adminpage']);


$app->run();
