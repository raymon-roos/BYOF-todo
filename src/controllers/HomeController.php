<?php

namespace controller;

class HomeController extends \service\ProviderService
{
    public function GETIndex()
    {
        echo $this->twig->render('home.html');
    }
}