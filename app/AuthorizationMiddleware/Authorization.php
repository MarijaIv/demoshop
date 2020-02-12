<?php


namespace Demoshop\AuthorizationMiddleware;


use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;


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
     * @return bool
     * @throws HttpUnauthorizedException
     */
    public static function handle(Request $request): bool
    {
        $serviceRegistry = ServiceRegistry::getInstance();
        $session = $serviceRegistry->get('Session');
        $cookie = $serviceRegistry->get('Cookie');
        if ($cookie->get('username') || $session->get('username')) {
            return true;
        }

        throw new HttpUnauthorizedException();

    }
}