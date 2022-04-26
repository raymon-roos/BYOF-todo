<?php

namespace controller;

class ErrorController
{
    public function GETPageUnknown($target)
    {
        http_response_code(404);
        global $twig;
        echo $twig->render(
            'error.html',
            ['error' => http_response_code() . " - $target not found"]
        );   
    }

    public function GETObjectNotFound()
    {
        global $twig;
        echo $twig->render(
            'error.html',
            ['error' => 'Sorry, but the object you were searching for does not exist, try something else.']
        ); 
    }

    public function GETInputIncorrect()
    {
        global $twig;
        echo $twig->render(
            'error.html',
            ['error' => 'Sorry, but you filled in something incorrect.']
        ); 
    }
}