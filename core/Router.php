<?php

namespace app\core;

use app\core\exception\NotFoundException;
require_once(dirname(__DIR__)."/core/exception/NotFoundException.php");

// Router class
class Router
{
    public Request $request;
    public Response $response;

    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        // if ($_SERVER['REQUEST_METHOD'] === 'GET' && $this->match($path)) {
        //     $callback();
        // }
        $this->routes['GET'][$path] = $callback;

    }

    public function post($path, $callback)
    {
        // if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->match($path)) {
        //     $callback();
        // }
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethodType();
        $callback = $this->routes[$method][$path] ?? false;

        // If callback is a string, render a view, else return 404
        if ($callback === false)
        {
            throw new NotFoundException();
        }
        if (is_string($callback))
        {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback))
        {
            // Application::$app->controller = new $callback[0]();
            // Application::$app->controller->action = $callback[1];
            $controller = new $callback[0]();
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            $callback[0] = $controller;

            foreach ($controller->middlewares as $middleware)
            {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }

}

