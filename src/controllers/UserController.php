<?php

namespace controller;

use RedBeanPHP\R as R;

class UserController extends \service\ProviderService
{
    public function GETLogin()
    {
        $error = !empty($_SESSION['errors']['login']) ? $_SESSION['errors']['login'] : "";

        echo $this->twig->render(
            'login.html',
            ['pageTitle' => 'login',
            'errorMessage' => $error ]
        );
    }

    public function POSTLogin()
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            if ($this->verifyUser($_POST['username'], $_POST['password'])) {
                header("location: /todo/viewLists");
                return;
            } else {
                $_SESSION['errors']['login'] = 'Incorrect username or password';
            }
        } else {
            $_SESSION['errors']['login'] = 'Missing username or password';
        }

        $this->GETLogin();
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

    private function verifyUser($username, $password) 
    {
        $user = R::findOne('users', ' username = ?', [ $username ]);

        if ($user && password_verify($password, $user->password)) {
            $this->setSessionToken($user);
            return true;
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