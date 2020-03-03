<?php

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidControllerOrActionException;
use Demoshop\Controllers\AdminControllers\{DashboardController};
use Demoshop\HTTP\RedirectResponse;
use Demoshop\Router;

require_once __DIR__ . '/../bootstrap.php';

try {
    Authorization::handle($request);

    if (empty($request->getGetData()['controller']) && empty($request->getPostData()['controller'])) {
        $response = (new DashboardController())->index($request);
    } else {
        $router = new Router();
        $response = $router->route($request);
    }
} catch (HttpUnauthorizedException $e) {
    $response = new RedirectResponse('/login.php');
} catch (ControllerOrActionNotFoundException $e) {
} catch (InvalidControllerOrActionException $e) {
}

$response->render();



