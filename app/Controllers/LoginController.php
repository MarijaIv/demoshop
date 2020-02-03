<?php

namespace Demoshop\Controllers;

use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;

class LoginController extends FrontController
{
    public function renderLogInPage(): void
    {
        $htmlResponse = new HTMLResponse(__DIR__ . '/../../resources/views/admin/login.php');
        $htmlResponse->render();
    }

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