<?php

namespace Demoshop\Controllers\FrontControllers;

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\Controllers\FrontController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Demoshop\HTTP\Request;
use Demoshop\HTTP\Response;
use Demoshop\Services\LoginService;

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
        try {
            Authorization::handle($request);
            return new RedirectResponse('/admin.php');
        } catch (HttpUnauthorizedException $e) {
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
                LoginService::login($request->getPostData()['username'],
                    $request->getPostData()['password'], $request->getPostData()['keepLoggedIn'])) {
            return new RedirectResponse('/admin.php');
        }

        return new RedirectResponse('/login.php');
    }
}