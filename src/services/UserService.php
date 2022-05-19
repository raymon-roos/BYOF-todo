<?php 

namespace service;

use RedBeanPHP\R as R;

class UserService extends \service\ProviderService
{
    public $loggedInUser;

    public function validateLoggedIn(): \RedBeanPHP\OODBBean | false
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        $session = R::findOne('sessions', ' token = ?', [ $_SESSION['token'] ]);

        return ($session) ?: false;
    }

    public function findLoggedInUserBySession(): \RedBeanPHP\OODBBean | false
    {
        $session = $this->validateLoggedIn();
        if ($session) {
            $user = R::findOne('users', 'id=?', [ $session->user_id ]);
        }

        return ($user) ?: false;
    }

    public function findUserByUsername($userName): \RedBeanPHP\OODBBean | false
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