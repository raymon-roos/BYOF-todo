<?php

namespace controller;

use service\UserService;
use controller\HomeController;
use controller\UserController;
use controller\ErrorController;

class RouterController extends ParentController
{
    private object $controller;

    public function __construct()
    {
        $this->chooseController();
    }

    private function chooseController()
    {
        $url = explode("/", $_GET["url"]);

        if (empty($url[0])) {
            $this->controller = new HomeController();
            $this->chooseMethod('index');
            return;
        }

        $controllerNameSpaced = 'controller\\' . ucfirst($url[0]) . 'Controller';

        if (!class_exists($controllerNameSpaced)) { 
            (new ErrorController())->GETPageUnknown($url[0]); 
            return;
        }

        $this->controller = new $controllerNameSpaced();

        if (!empty($url[1])) {
            header("X-Controller: {$controllerNameSpaced}");
            $this->chooseMethod($url[1]); 
            return;
        }
    }

    private function chooseMethod($url)
    {
        $method = $_SERVER['REQUEST_METHOD'] . ucfirst($url); 

        if ($method == 'POSTLogin' && method_exists($this->controller, 'POSTLogin')) {
            $this->controller->POSTLogin();
            return;
        }

        if (!(new UserService())->validateLoggedIn()) {
            (new UserController())->GETLogin();
            return;
        }

        if (
            is_numeric($url) &&
            method_exists($this->controller, 'selectByID') 
        ) {
            $this->controller->selectByID($url);
            exit();
        }

        if (method_exists($this->controller, $method)) {
            $this->controller->$method();
            header("X-Method: $method");
            exit();
        }
        
        (new ErrorController())->GETPageUnknown($url); 
        exit();
    }
}