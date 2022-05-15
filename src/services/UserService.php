<?php 

namespace service;

use RedBeanPHP\R as R;
use controller\UserController as UserController;

class UserService extends \service\ProviderService
{
    public $loggedInUser;

    public function validateLoggedIn()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        $token = R::find('sessions', ' token = ?', [ $_SESSION['token'] ]);

        return ($token) ?: false;
    }

    public function checkLoggedInUserBySession() 
    {
        if (!isset($_SESSION['token'])) {
            (new UserController())->GETLogin();
            return;
        } 

        $user = R::findOne('sessions', ' token = ?', [ $_SESSION['token'] ])->user;

        return ($user) ?: false;
    }

    public function findUserByUsername($userName)
    {
        $user = R::findOne('users', ' username = ?', [ $userName ]);

        return ($user) ? $user : false;
    }

    private function setLoggedInUser()
    {
        $checkUser = $this->checkLoggedInUserBySession();
        if ($checkUser) {
            $this->loggedInUser = $checkUser;
            return;
        }
        (new UserController())->GETLogin();
    }
}