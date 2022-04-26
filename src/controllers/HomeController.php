<?php

namespace controller;

class HomeController
{
    public function GETIndex()
    {
        global $twig;
        echo $twig->render(
            'home.html',
            ['pageTitle' => 'home']
        );
    }
}