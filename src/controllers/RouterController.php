<?php

declare(strict_types=1);

namespace controller;

use service\UserService;

final class RouterController extends ParentController
{
    public function chooseController(): void
    {
        $url = explode("/", $_GET["url"]);

        $controllerNamespaced = 'controller\\' . ucfirst(($url[0]) ?: 'home') . 'Controller';

        if (!class_exists($controllerNamespaced)) {
            (new ErrorController())->GETPageUnknown($url[0]);
            return;
        }

        header("X-Controller: {$controllerNamespaced}");
        $this->chooseMethod(new $controllerNamespaced(), ($url[1]) ?? 'index');
    }

    private function chooseMethod(object $controller, string $url): void
    {
        $method = $_SERVER['REQUEST_METHOD'] . ucfirst($url);


        if ($method == 'POSTLogin' && method_exists($controller, 'POSTLogin')) {
            $controller->POSTLogin();
            return;
        }

        if (!UserService::validateLoggedIn()) {
            (new UserController())->GETLogin();
            return;
        }

        if (
            is_numeric($url) &&
            method_exists($controller, 'selectByID')
        ) {
            $controller->selectByID(intval($url));
            return;
        }

        if (method_exists($controller, $method)) {
            try {
                $controller->$method();
                header("X-Method: $method");
                return;
            } catch (\Throwable) {
                (new ErrorController())->internalServerError();
            }
        }

        (new ErrorController())->GETPageUnknown($url);
    }
}
