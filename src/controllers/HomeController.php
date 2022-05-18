<?php

namespace controller;

class HomeController extends ParentController
{
    public function GETIndex()
    {
        $this->viewService->displayPage('home');
    }
}