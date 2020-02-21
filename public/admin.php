<?php

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\Controllers\AdminControllers\{DashboardController};
use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidControllerOrActionException;
use Demoshop\HTTP\RedirectResponse;
use Demoshop\Router;

require_once __DIR__ . '/../bootstrap.php';

try {
    Authorization::handle($request);

    if ($request->getGetData()['controller'] === null) {
        $response = (new DashboardController())->index($request);
        $response->render();
    } else {
        $router = new Router();
        $response = $router->route($request);
        $response->render();
    }
} catch (HttpUnauthorizedException $e) {
    $redirect = new RedirectResponse('/login.php');
    $redirect->render();
} catch (ControllerOrActionNotFoundException $e) {
} catch (InvalidControllerOrActionException $e) {
}





