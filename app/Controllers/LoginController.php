<?php

namespace Demoshop\Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;

class LoginController extends FrontController
{
    public function renderLogInPage(): void
    {
        self::render(__DIR__ . '/../../resources/views/admin/login.php');
    }

    public function logIn(string $username, string $password, string $keepLoggedIn): void
    {
        if (self::authenticate($username, $password)) {
            if ($keepLoggedIn === 'on') {
                setcookie('loggedIn', true);
            } else {
                setcookie('loggedIn', false);
            }
            header('Location: /admin.php');
            exit();
        }

        header('Location: /login.php');
    }

    public function loggedIn(): void
    {
        header('Location: /admin.php');
        exit();
    }

    private static function authenticate(string $username, string $password): bool
    {
        return Capsule::table('admin')
            ->where('username', '=', $username)
            ->where('password', '=', $password)
            ->exists();
    }

    private static function render(string $file): void
    {
        include $file;
    }
}