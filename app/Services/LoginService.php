<?php

namespace Demoshop\Services;

use Demoshop\Repositories\AdminRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;

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
    public static function login($username, $password, $keepLoggedIn): bool
    {
        $adminRepository = new AdminRepository();
        $result = $adminRepository->adminExists($username);

        if ($result) {
            $foundUser = false;
            for ($i = 0; $i < $result->count(); $i++) {
                if ($result[$i]->password === md5($password)) {
                    $foundUser = true;
                    break;
                }
            }

            if ($foundUser) {
                if ($keepLoggedIn) {
                    $cookie = ServiceRegistry::get('Cookie');
                    $cookie->add('username', $username, time() + 60);
                    $cookie->add('password', md5($password), time() + 60);
                    return true;
                }
                $session = ServiceRegistry::get('Session');
                $session->add('username', $username);
                $session->add('password', md5($password));

                return true;
            }

            return false;
        }

        return false;
    }
}