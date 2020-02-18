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
     * Function for verifying admin credentials.
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
                    $hash = md5($username) . md5($password) . sha1('demoshop');
                    $cookie->add('user', $hash, time() + 120);
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

    /**
     * Function for validating cookie content.
     * @param string $user
     * @return bool
     */
    public static function validate(string $user): bool
    {
        $adminRepository = new AdminRepository();
        $admins = $adminRepository->getAllAdmins();

        for ($i = 0; $i < $admins->count(); $i++) {
            $us = md5($admins[$i]->username);
            $pass = $admins[$i]->password;
            if (strpos($user, $admins[$i]->password) !== false && strpos($user, md5($admins[$i]->username)) !== false) {
                return true;
            }
        }

        return false;
    }
}