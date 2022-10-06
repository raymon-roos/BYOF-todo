<?php

declare(strict_types=1);

namespace controller;

use service\DatabaseConnectionService as dbCon;
use service\ViewService;

class ParentController
{
    protected ViewService $viewService;

    public function __construct()
    {
        $this->viewService = (new \service\ViewService());
        dbCon::connectDB();
    }
}
