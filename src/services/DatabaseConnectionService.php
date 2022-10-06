<?php

namespace service;

use RedBeanPHP\R as R;

class DatabaseConnectionService
{
    public static function connectDB(): bool | \RedBeanPHP\ToolBox
    {
        return R::testConnection() ?: R::setup(
            'mysql:host=localhost;dbname=todo',
            'bit_academy',
            'bit_academy' 
        );
    }    
}
