<?php


namespace Demoshop\AuthorizationMiddleware;


use Demoshop\HTTP\RedirectResponse;
use Demoshop\Services\LoginService;


/**
 * Class Authorization
 * @package Demoshop\AuthorizationMiddleware
 */
class Authorization
{
    /**
     * Function for authenticating admin.
     *
     * @param string $username
     * @param string $password
     * @param string $keepLoggedIn
     * @return bool
     */
    public static function authenticate(string $username, string $password, string $keepLoggedIn): bool
    {
       return LoginService::verifyCredentials($username, $password, $keepLoggedIn);
    }

    /**
     * If admin is authenticated, redirect to admin.php page.
     * If admin is not authenticated, render login.php page.
     *
     * @param string $username
     * @param string $password
     * @param string $keepLoggedIn
     * @return void
     */
    public static function redirection(string $username, string $password, string $keepLoggedIn): void
    {
        if (isset($_SESSION['username']) || $_COOKIE['username'] === $username) {
            $redirectResponse = new RedirectResponse('/admin.php');
            $redirectResponse->render();
        } else {
            if (self::authenticate($username, $password, $keepLoggedIn)) {
                $redirectResponse = new RedirectResponse('/admin.php');
                $redirectResponse->render();
            }
            $redirectResponse = new RedirectResponse('/login.php');
            $redirectResponse->render();
        }
    }


}