<?php

namespace controller;

use service\UserService;
use controller\HomeController;
use controller\UserController;
use controller\ErrorController;

class RouterController
{
    private $controller;

    public function __construct()
    {
        $this->checkIfLoggedIn();
    }

    private function checkIfLoggedIn()
    {
        $isLoginValid = (new UserService())->validateLoggedIn();

        if ($isLoginValid) {
            $this->chooseController();
            return;
        }

        (new UserController())->POSTLogin();
    }

    private function chooseController()
    {
        $url = explode("/", $_GET["url"]);

        if (empty($url[0])) {
            $controller = new HomeController();
            $controller->GETIndex();
            exit();
        }

        $controllerName = str_replace('/', '', ucfirst($url[0])) . "Controller";
        $controllerNameSpaced = "controller\\" . $controllerName;
        $controllerPath = $controllerName . ".php";

        if (!file_exists("./controllers/$controllerPath")) { 
            (new ErrorController())->GETPageUnknown($url[0]); 
            return;
        }

        require_once("./controllers/$controllerPath");
        $this->controller = new $controllerNameSpaced();

        if (!empty($url[1])) {
            $this->chooseMethod($url[1]); 
            return;
        }
    }

    private function chooseMethod($url)
    {
        if (
            is_numeric($url) &&
            method_exists($this->controller, 'selectByID') 
        ) {
            $this->controller->selectByID($url);
            exit();
        }

        $method = $_SERVER['REQUEST_METHOD'] . ucfirst($url); 

        if (method_exists($this->controller, $method)) {
            $this->controller->$method();
            exit();
        }

        (new ErrorController())->GETPageUnknown($url); 
        exit();
    }
}