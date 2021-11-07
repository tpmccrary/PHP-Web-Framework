<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\models\SignInForm;
use app\models\User;
use app\core\middlewares\AuthMiddleware;
require_once("../models/User.php");
require_once("../models/SignInForm.php");

use app\core\Response;
use app\models\Admin;
use app\models\ChangePasswordModel;
require_once("../models/ChangePasswordModel.php");
require_once("../models/Admin.php");


require_once("../controllers/Controller.php");
require_once("../core/middlewares/AuthMiddleware.php");

class AuthController extends Controller
{

    // Contstructor
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['userpage', 'adminpage']));
    }

    public function signin(Request $request, Response $response)
    {
        $signinForm = new SignInForm();
        if ($request->isPost()) {
            $signinForm->loadData($request->getBody());

            if ($signinForm->validate() && $signinForm->signin()) {
                $response->redirect('/');
                return;
            }
        }
        // $this->setLayout("signedin");
        return $this->render('signin', ['model' => $signinForm]);
    }

    public function signup(Request $request)
    {
        $signupModel = new User();

        if ($request->isPost()) {
            $signupModel->loadData($request->getBody());

            if ($signupModel->validate() && $signupModel->signup()) {
                Application::$app->session->setFlash('success', "You have successfully signed up!");
                Application::$app->response->redirect('/');
                exit;   
            }

            return $this->render('signup', [
                'signupModel' => $signupModel
            ]);
        }
        
        // $this->setLayout("signedin");
        return $this->render('signup', [
            'signupModel' => $signupModel
        ]);
    }

    public function signout(Request $request, Response $response)
    {
        Application::$app->signout();
        $response->redirect('/');
    }

    public function userPage(Request $request, Response $response)
    {
        $changePassword = new ChangePasswordModel();
        if ($request->isPost()) {
            $changePassword->loadData($request->getBody());

            if ($changePassword->validate() && $changePassword->changePassword()) {
                Application::$app->session->setFlash('success', "You have successfully changed your password!");
                $response->redirect('/user-page');

                return;
            }
        }
        return $this->render('user', ['model' => $changePassword]);
    }

    public function adminpage(Request $request, Response $response)
    {
        $user = new Admin();

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->signup()) {
                Application::$app->session->setFlash('success', "You have successfully created a new user!");
                $response->redirect('/admin-page');
                return;  
            }
        }

        $user->allUsers = $user->getAllUsers();
        
        // $this->setLayout("signedin");
        return $this->render('admin', [
            'model' => $user
        ]);
    }
        
}