<?php

namespace controller;

class ErrorController extends \service\ProviderService
{
    // public function __construct()
    // {
    //     // $this->renderErrorMessage();
    // }
    
    public function renderErrorMessage()
    {
        echo $this->twig->render(
            'error.html',
            ['errors' => $_SESSION['errors']]
        );   
    }

    public function GETPageUnknown($target)
    {
        http_response_code(404);
        echo $this->twig->render(
            'error.html',
            ['error' => http_response_code() . " - $target not found"]
        );   
    }

    public function GETObjectNotFound()
    {
        echo $this->twig->render(
            'error.html',
            ['error' => 'Sorry, but the object you were searching for does not exist, try something else.']
        ); 
    }

    public function GETInputIncorrect()
    {
        echo $this->twig->render(
            'error.html',
            ['error' => 'Sorry, but you filled in something incorrect.']
        ); 
    }

    public function GETDebug($dump)
    {
        echo $this->twig->render(
            'error.html',
            ['error' => $dump]
        ); 
    }
}