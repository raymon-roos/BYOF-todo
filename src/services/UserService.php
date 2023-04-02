<?php 

declare(strict_types=1);

namespace service;

use RedBeanPHP\R as R;
use RedBeanPHP\OODBBean as Bean;

class UserService
{
    public $loggedInUser;

    public static function validateLoggedIn(): Bean | false
    {
        $session = R::findOne('sessions', ' token = ?', [ $_SESSION['token'] ?? '' ]);

        return $session ?? false;
    }

    public static function findLoggedInUserBySession(): Bean | false
    {
        $session = self::validateLoggedIn();
        if ($session) {
            $user = R::findOne('users', 'id=?', [ $session->user_id ]);
        }

        return $user ?: false;
    }

    public static function findUserByUsername(string $userName): Bean | false
    {
        $user = R::findOne('users', ' username = ?', [ $userName ]);

        return $user ?: false;
    }

    public static function verifyLogin(string $username, string $password): Bean | false
    {
        $user = R::findOne('users', ' username = ?', [ $username ]);

        return ($user && password_verify($password, $user->password)) ? $user : false;
    }

    public static function setSessionToken(Bean $user): void
    {
        $_SESSION['token'] = base64_encode(random_bytes(89));

        $setToken = R::dispense('sessions');
        $setToken->user = $user;
        $setToken->token = $_SESSION['token'];  
        R::store($setToken, true);
    }
}
