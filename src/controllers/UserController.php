<?php

namespace controller;

use RedBeanPHP\R as R;

class UserController extends ParentController
{
    public function GETLogin(string $warning = '')
    {
        $this->viewService->displayPage('login', ['warning' => $warning]);
    }

    public function POSTLogin()
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $loginAttempt = $this->verifyLogin($_POST['username'], $_POST['password']);
            if ($loginAttempt) {
                $this->setSessionToken($loginAttempt);
                header("location: /todo/viewLists");
                return;
            } else {
                $this->GETLogin('Incorrect username or password');
            }
        } else {
            $this->GETLogin('Missing username or password');
        }
    }

    public function GETCreateUser()
    {
        $newUser = R::dispense('users');
        $newUser->username = 'user';
        $newUser->password = password_hash('user', PASSWORD_DEFAULT);
        R::store($newUser);
    }

    public function GETLogout()
    {
        $token = (!empty($_SESSION['token'])) ? $token = $_SESSION['token'] : $token = "";

        R::hunt('sessions', 'token = ?', [$token]);
        session_unset();
        session_destroy();
        $this->GETLogin();
    }

    private function verifyLogin($username, $password) 
    {
        $user = R::findOne('users', ' username = ?', [ $username ]);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    private function setSessionToken($user)
    {
        $_SESSION['token'] = base64_encode(random_bytes(89));

        $setToken = R::dispense('sessions');
        $setToken->user = $user;
        $setToken->token = $_SESSION['token'];  
        R::store($setToken, true);
    }
}