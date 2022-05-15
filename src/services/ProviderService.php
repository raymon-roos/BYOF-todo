<?php

namespace service;

use service\DatabaseConnectionService as dbCon;

class ProviderService
{
    public object $twig;

    public function __construct()
    {
        $this->twig = (new TwigService())->startTwig();
        (new dbCon())->connectDB();
    }
}