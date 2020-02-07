<?php

namespace Demoshop\Controllers\FrontControllers;

use Demoshop\AuthorizationMiddleware\Authorization;
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
     * Function for logging in.
     *
     * @param string $username
     * @param string $password
     * @param string $keepLoggedIn
     *
     * @return void
     */
    public function logIn(string $username, string $password, string $keepLoggedIn): void
    {
        Authorization::redirection($username, $password, $keepLoggedIn);
    }
}