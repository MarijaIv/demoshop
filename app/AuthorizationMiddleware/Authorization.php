<?php


namespace Demoshop\AuthorizationMiddleware;


use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Demoshop\Services\LoginService;


/**
 * Class Authorization
 * @package Demoshop\AuthorizationMiddleware
 */
class Authorization
{
    /**
     * If admin is authenticated, redirect to dashboard page.
     * If admin is not authenticated, render login page.
     *
     * @param Request $request
     * @return void
     * @throws HttpUnauthorizedException
     */
    public static function handle(Request $request): void
    {
        $session = ServiceRegistry::get('Session');
        $cookie = ServiceRegistry::get('Cookie');
        /** @var LoginService $loginService */
        $loginService = ServiceRegistry::get('LoginService');

        if (!$session->get('username') && (!$cookie->get('user') || !($loginService->validate($cookie->get('user'))))) {
            throw new HttpUnauthorizedException('Admin is not authorized');
        }
    }
}