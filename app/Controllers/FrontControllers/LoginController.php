<?php

namespace Demoshop\Controllers\FrontControllers;

use Demoshop\Controllers\FrontController;
use Demoshop\Cookie\CookieManager;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Demoshop\HTTP\Request;
use Demoshop\HTTP\Response;
use Demoshop\Services\LoginService;
use Demoshop\Session\PHPSession;

/**
 * Class LoginController
 * @package Demoshop\Controllers\FrontControllers
 */
class LoginController extends FrontController
{
    /**
     * Function for rendering login.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function renderLogInPage(Request $request): Response
    {
        $cookie = new CookieManager();
        $session = new PHPSession();
        if($cookie->get('username') || $session->get('username')) {
            return new RedirectResponse('/admin.php');
        }
        return new HTMLResponse('/views/admin/login.php');
    }

    /**
     * Function for logging in.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logIn(Request $request): RedirectResponse
    {

        if ($request->getPostData()['username'] !== null &&
                LoginService::verifyCredentials($request->getPostData()['username'],
                    $request->getPostData()['password'], $request->getPostData()['keepLoggedIn'])) {
            return new RedirectResponse('/admin.php');
        }

        return new RedirectResponse('/login.php');
    }
}