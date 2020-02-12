<?php

namespace Demoshop\Services;

use Demoshop\Cookie\CookieManager;
use Demoshop\Repositories\AdminRepository;
use Demoshop\Session\PHPSession;

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
        $result = $adminRepository->adminExists($username);

        if($result) {
            if ($keepLoggedIn) {
                $cookie = new CookieManager();
                $cookie->add('username', $username, time() + 60);
                $cookie->add('password', md5($password), time() + 60);
                return true;
            }
            $session = new PHPSession();
            $session->add('username', $username);
            $session->add('password', md5($password));

            return true;
        }

        return false;
    }
}