<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';

    public array $middlewares = [];

    public string $action = '';

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function setLayout($layout)
    {
        // This layout equals the givne layout
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleWare $middleware)
    {
        $this->middlewares[] = $middleware;
    }
}