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
     * @var AdminRepository
     */
    public $adminRepository;

    /**
     * LoginService constructor.
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * Function for verifying admin credentials.
     *
     * @param $username
     * @param $password
     * @param $keepLoggedIn
     * @return bool
     */
    public function login($username, $password, $keepLoggedIn): bool
    {
        $admin = $this->adminRepository->getAdminWithUsername($username);

        if (!$admin) {
            return false;
        }

        if ($admin->password !== md5($password)) {
            return false;
        }

        if ($keepLoggedIn) {
            $cookie = ServiceRegistry::get('Cookie');
            $hash = $username . ' ' . md5('demoshop');
            $cookie->add('user', $hash, time() + 3600);

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
    public function validate(string $user): bool
    {
        $username = explode(' ', trim($user))[0];
        $key = explode(' ', trim($user))[1];

        return !(!$this->adminRepository->adminExists($username) || $key !== md5('demoshop'));
    }
}