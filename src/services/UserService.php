<?php 

namespace service;

use RedBeanPHP\R as R;
use service\DatabaseConnectionService as dbCon;

class UserService
{
    public function __construct()
    {
        (new dbCon())->connectDB();
    }

    public function validateLoggedIn()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        $token = R::find('sessions', ' token = ?', [ $_SESSION['token'] ]);

        if ($token) {
            return true;
        }
        return false;
    }
}