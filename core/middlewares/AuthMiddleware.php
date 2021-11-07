<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

require_once(dirname(__DIR__)."/exception/ForbiddenException.php");
require_once(dirname(__DIR__)."/middlewares/BaseMiddleware.php");

class AuthMiddleware extends BaseMiddleware
{

    public array $actions = [];

    //Constructor
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isVisitor())
        {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions))
            {
                throw new ForbiddenException();
            }
        }
        else if (Application::$app->user->type === "user")
        {
            if (empty($this->actions) || in_array(Application::$app->controller->action, ['adminpage']) && Application::$app->user->type === "user") 
            {
                throw new ForbiddenException();
            }
        }
    }
}