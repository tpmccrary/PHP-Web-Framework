<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;

require_once(dirname(__DIR__)."/controllers/Controller.php");

class ViewController extends Controller
{

    public function mainpage()
    {
        $params = [
            'name' => 'Tim',
        ];
        return $this->render('mainpage', $params);
    }

    public function about()
    {
        return $this->render('about');
    }

}