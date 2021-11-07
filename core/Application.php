<?php

namespace app\core;

use app\controllers\Controller;
use app\models\DatabaseModel;

require_once(dirname(__DIR__)."/core/Request.php");
require_once(dirname(__DIR__)."/core/Router.php");
require_once(dirname(__DIR__)."/core/Response.php");
require_once(dirname(__DIR__)."/core/Database.php");
require_once(dirname(__DIR__)."/controllers/Controller.php");
require_once(dirname(__DIR__)."/core/Session.php");
require_once(dirname(__DIR__)."/models/DatabaseModel.php");
require_once(dirname(__DIR__)."/core/View.php");

class Application
{
    public static string $ROOT_DIR;

    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $database;
    public static Application $app;
    public Controller $controller;
    public Session $session;  
    public ?DatabaseModel $user;
    public string $layout = 'main';
    public View $view;

    // constructor
    public function __construct($rootDir, $config)
    {
        self::$ROOT_DIR = $rootDir;

        self::$app = $this;
        // create a request
        $this->request = new Request();

        // create a response
        $this->response = new Response();

        // create a router
        $this->router = new Router($this->request, $this->response);

        $this->database = new Database($config['database']);

        // New controller
        $this->controller = new Controller();

        $this->view = new View();

        // New session
        $this->session = new Session();

        // New user
        $this->user = null;

        $this->userClass = $config['userClass'];
        $primaryKey = $this->userClass::primaryKey();
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }

    }

    // run the application
    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {

            if (is_string($e->getCode()))
            {
                $this->response->setStatusCode(500);
            }
            else
            {
                $this->response->setStatusCode($e->getCode());
            }
            // Set status code
            echo $this->view->renderView('_error', ['exception' => $e]);
        }

        
    }

    public function signin(DatabaseModel $user)
    {
        $this->user = $user;

        $primaryKey = $user->primaryKey();

        $primaryValue = $user->{$primaryKey};
        $this->session->set("user", $primaryValue);

        return true;
    }

    public function signout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
    public function isVisitor()
    {
        return !self::$app->user;
    }
}