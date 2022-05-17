<?php 

namespace service;

use RedBeanPHP\R as R;

class UserService extends \service\ProviderService
{
    public $loggedInUser;

    public function validateLoggedIn()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        $sessionBean = R::findOne('sessions', ' token = ?', [ $_SESSION['token'] ]);

        return ($sessionBean) ?: false;
    }

    public function findLoggedInUserBySession() 
    {
        $sessionBean = $this->validateLoggedIn();
        if ($sessionBean) {
            $userBean = R::findOne('users', 'id=?', [ $sessionBean->user_id ]);
        }

        return ($userBean) ?: false;
    }

    public function findUserByUsername($userName)
    {
        $user = R::findOne('users', ' username = ?', [ $userName ]);

        return ($user) ?: false;
    }

    private function setLoggedInUser()
    {
        $checkUser = $this->findLoggedInUserBySession();
        if ($checkUser) {
            $this->loggedInUser = $checkUser;
        }
    }
}