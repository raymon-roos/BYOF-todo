<?php

namespace service;

use service\DatabaseConnectionService as dbCon;

class ProviderService
{
    public function __construct()
    {
        (new dbCon())->connectDB();
    }
}