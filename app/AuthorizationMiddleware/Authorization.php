<?php


namespace Demoshop\AuthorizationMiddleware;


use Demoshop\HTTP\RedirectResponse;
use Illuminate\Database\Capsule\Manager as Capsule;

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
        $result = Capsule::table('admin')->select('username', 'password')
            ->where('username', '=', $username)
            ->where('password', '=', $password)->get();
        if ($result !== null) {
            if ($keepLoggedIn === 'on') {
                setcookie('username', $username, time() + 15);
                setcookie('password', $password, time() + 15);
                return true;
            }
            $_SESSION['username'] = $result[0]->username;
            $_SESSION['password'] = md5($result[0]->password);
            setcookie('loggedIn', '');
            return true;
        }

        return false;
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