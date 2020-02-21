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
     * If admin is authenticated, redirect to admin.php page.
     * If admin is not authenticated, render login.php page.
     *
     * @param Request $request
     * @return void
     * @throws HttpUnauthorizedException
     */
    public static function handle(Request $request): void
    {
        $session = ServiceRegistry::get('Session');
        $cookie = ServiceRegistry::get('Cookie');

        if (!($cookie->get('user') || $session->get('username'))) {
            throw new HttpUnauthorizedException();
        }

        if (!(LoginService::validate($cookie->get('user'))
            || !strpos($cookie->get('user'), md5('demoshop')))) {
            throw new HttpUnauthorizedException();
        }
    }
}