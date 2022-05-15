<?php

namespace service;

class TwigService
{
    public function startTwig() 
    {
        $loader = new \Twig\Loader\FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader);
        return $twig;
    }
}