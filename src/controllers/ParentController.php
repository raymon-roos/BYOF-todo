<?php

declare(strict_types=1);

namespace controller;

use service\ViewService;

class ParentController extends \service\ProviderService
{
    protected ViewService $viewService;

    public function __construct()
    {
        $this->viewService = (new \service\ViewService());
        (new \service\ProviderService());
        // $this->viewService->displayPage('index', []);
    }
}