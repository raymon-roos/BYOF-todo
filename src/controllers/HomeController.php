<?php

declare(strict_types=1);

namespace controller;

class HomeController extends ParentController
{
    public function GETIndex(): void
    {
        $this->viewService->displayPage('home');
    }
}
