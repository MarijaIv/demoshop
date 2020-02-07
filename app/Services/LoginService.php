<?php

namespace Demoshop\Services;

use Demoshop\Repositories\AdminRepository;

/**
 * Class LoginService
 * @package Demoshop\Services
 */
class LoginService
{
    /**
     * Function for verifying admin credentials
     *
     * @param $username
     * @param $password
     * @param $keepLoggedIn
     * @return bool
     */
    public static function verifyCredentials($username, $password, $keepLoggedIn): bool
    {
        $adminRepository = new AdminRepository();
        $result = $adminRepository->getAdmin($username, $password);
        if($result) {
            if ($keepLoggedIn === 'on') {
                setcookie('username', $username, time() + 15);
                setcookie('password', $password, time() + 15);
                return true;
            }
            $_SESSION['username'] = $username;
            $_SESSION['password'] = md5($password);
            setcookie('loggedIn', '');
            return true;
        }
        return false;
    }
}