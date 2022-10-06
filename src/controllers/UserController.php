<?php

namespace controller;

use RedBeanPHP\R as R;
use service\UserService as UserService;

class UserController extends ParentController
{
    public function GETLogin(string $warning = ''): void
    {
        $this->viewService->displayPage('login', ['warning' => $warning]);
    }

    public function POSTLogin(): void
    {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $this->GETLogin('Missing username or password');
            return;
        }

        $userAttemptingLogin = UserService::verifyLogin($_POST['username'], $_POST['password']);

        if (!$userAttemptingLogin) {
            $this->GETLogin('Incorrect username or password');
            return;
        }

        UserService::setSessionToken($userAttemptingLogin);
        header("location: /todo/viewLists");
    }

    public function GETCreateUser(): void
    {
        $newUser = R::dispense('users');
        $newUser->username = 'user';
        $newUser->password = password_hash('user', PASSWORD_DEFAULT);
        R::store($newUser);
    }

    public function GETLogout(): void
    {
        R::hunt('sessions', 'token = ?', [($_SESSION['token']) ?? '']);
        session_destroy();
        $this->GETLogin();
    }
}
