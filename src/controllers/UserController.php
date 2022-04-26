<?php

namespace controller;

use RedBeanPHP\R as R;
use service\DatabaseConnectionService as dbCon;

class UserController
{
    public function __construct()
    {
        (new dbCon())->connectDB();
    }

    public function GETLogin()
    {
        $errorMessage = (!empty($_SESSION['errorMessage'])) ? $errorMessage = $_SESSION['errorMessage'] : $errorMessage = "";

        global $twig;
        echo $twig->render(
            'login.html',
            ['pageTitle' => 'login',
            'errorMessage' => $errorMessage]
        );
        unset($_SESSION['errorMessage']);
    }

    public function POSTLogin()
    {
        if (isset($_POST['login'])) {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                if ($this->verifyUser($_POST['username'], $_POST['password'])) {
                    $_SESSION['token'] = base64_encode(random_bytes(89));
                    $this->setSessionToken($_POST['username'], $_SESSION['token']);
                    header("location: /");
                    exit();
                } else {
                    $_SESSION['errorMessage'] = 'Incorrect username or password';
                    $this->GETLogin();
                }
            } else {
                $_SESSION['errorMessage'] = 'Missing username or password';
                $this->GETLogin();
            }
        } else {
            $this->GETLogin();
        }
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
            return true;
        }
        return false;
    }

    public function verifySessionToken()
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

    private function setSessionToken($username, $newToken)
    {
        $setToken = R::dispense('sessions');
        $setToken->username = $username;
        $setToken->token = $newToken;
        R::store($setToken, true);
    }
}