<?php

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\Controllers\AdminControllers\{DashboardController};
use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\HTTP\RedirectResponse;
use Demoshop\Router;

require_once __DIR__ . '/../bootstrap.php';

try {
    if ($request->getGetData()['controller'] === null && Authorization::handle($request)) {
        $response = (new DashboardController())->index($request);
        $response->render();
    } else {
        $router = new Router();
        $router->route($request);
    }
} catch (HttpUnauthorizedException $e) {
    $redirect = new RedirectResponse('/error.php');
    $redirect->render();
}





