<?php

namespace Demoshop\Controllers;

use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;

class LoginController extends FrontController
{
    // function for rendering login.php page
    public function renderLogInPage(): void
    {
        $htmlResponse = new HTMLResponse(__DIR__ . '/../../resources/views/admin/login.php');
        $htmlResponse->render();
    }

    /* function for authenticating admin
    * if admin is authenticated, redirect to admin.php page
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

    /* function for redirecting to admin.php page if
    * admin checked Keep me logged in checkbox
    */
    public function loggedIn(): void
    {
        $redirectResponse = new RedirectResponse('/admin.php');
        $redirectResponse->render();
    }

    private static function authenticate(string $username, string $password): bool
    {
        return Capsule::table('admin')
            ->where('username', '=', $username)
            ->where('password', '=', $password)
            ->exists();
    }
}