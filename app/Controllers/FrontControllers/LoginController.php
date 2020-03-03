<?php

namespace Demoshop\Controllers\FrontControllers;

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\Controllers\FrontController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\RedirectResponse;
use Demoshop\HTTP\Request;
use Demoshop\HTTP\Response;
use Demoshop\ServiceRegistry\ServiceRegistry;
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
        if($this->isLoggedIn()) {
            return new RedirectResponse('/admin.php');
        }

        return new HTMLResponse('/views/admin/login.php');
    }

    /**
     * Function for logging in.
     *
     * @param Request $request
     * @return Response
     */
    public function logIn(Request $request): Response
    {
        $data = $request->getPostData();
        $response = new RedirectResponse('/admin.php');
        $loginService = ServiceRegistry::get('LoginService');

        if(empty($data['username']) || empty($data['password'])) {
            $response = new HTMLResponse('/views/admin/login.php');
        }

        if(!$loginService->login($data['username'], $data['password'], $data['keepLoggedIn'] ?? false)) {
            $response = new HTMLResponse('/views/admin/login.php');
        }

        return $response;
    }

    /**
     * Check if admin is in session or cookie.
     *
     * @return bool
     */
    private function isLoggedIn(): bool
    {
        $session = ServiceRegistry::get('Session');
        $cookie = ServiceRegistry::get('Cookie');

        return !(!$session->get('username') && !$cookie->get('user'));
    }
}