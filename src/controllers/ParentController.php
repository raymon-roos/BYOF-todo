<?php

declare(strict_types=1);

namespace controller;

use service\DatabaseConnectionService as dbCon;
use service\ViewService as ViewService;

class ParentController
{
    public function __construct(
        protected ViewService $viewService = new ViewService()
    ) {
        (new dbCon())->connectDB();
    }
}
