<?php

namespace Demoshop\Controllers\FrontControllers;

use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;
use Demoshop\Controllers\FrontController;

/**
 * Class LoginController
 * @package Demoshop\Controllers\FrontControllers
 */
class LoginController extends FrontController
{
    /**
     * Function for rendering login.php page.
     *
     * @return void
     */
    public function renderLogInPage(): void
    {
        $htmlResponse = new HTMLResponse(__DIR__ . '/../../../resources/views/admin/login.php');
        $htmlResponse->render();
    }

    /**
     * Function for authenticating admin.
     * If admin is authenticated, redirect to admin.php page.
     *
     * @param string $username
     * @param string $password
     * @param string $keepLoggedIn
     */
    public function logIn(string $username, string $password, string $keepLoggedIn): void
    {

        if (self::authenticate($username, $password)) {
            if ($keepLoggedIn === 'on') {
                setcookie('loggedIn', true);
            } else {
                setcookie('loggedIn', false);
            }
            $redirectResponse = new RedirectResponse('/admin.php');
            $redirectResponse->render();
        }
        $redirectResponse = new RedirectResponse('/login.php');
        $redirectResponse->render();
    }

    /**
     * Function for redirecting to admin.php page if
     * admin checked Keep me logged in checkbox.
     *
     * @return void
     */
    public function loggedIn(): void
    {
        $redirectResponse = new RedirectResponse('/admin.php');
        $redirectResponse->render();
    }

    /**
     * Function for authenticating admin.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    private static function authenticate(string $username, string $password): bool
    {
        return Capsule::table('admin')
            ->where('username', '=', $username)
            ->where('password', '=', $password)
            ->exists();
    }
}