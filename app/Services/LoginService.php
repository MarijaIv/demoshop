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

        if (!$adminRepository->adminExists($username)) {
            return false;
        }

        $admin = $adminRepository->getAdminWithUsername($username);

        if($admin->password !== md5($password)) {
            return false;
        }

        if ($keepLoggedIn) {
            $cookie = ServiceRegistry::get('Cookie');
            $hash = $username . md5('demoshop');
            $cookie->add('user', $hash, time() + 120);

            return true;
        }

        $session = ServiceRegistry::get('Session');
        $session->add('username', $username);
        $session->add('password', md5($password));

        return true;
    }

    /**
     * Function for validating cookie content.
     *
     * @param string $user
     * @return bool
     */
    public static function validate(string $user): bool
    {
        $adminRepository = new AdminRepository();
        $admins = $adminRepository->getAllAdmins();

        for ($i = 0; $i < $admins->count(); $i++) {
            if (strpos($user, $admins[$i]->username) !== false) {
                return true;
            }
        }

        return false;
    }
}