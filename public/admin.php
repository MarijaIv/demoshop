<?php

use Demoshop\AuthorizationMiddleware\Authorization;
use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\HttpUnauthorizedException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidControllerOrActionException;
use Demoshop\Controllers\AdminControllers\{DashboardController, ProductController};
use Demoshop\HTTP\RedirectResponse;
use Demoshop\Router;

require_once __DIR__ . '/../bootstrap.php';

try {
    Authorization::handle($request);

    if ($request->getGetData()['controller'] === null) {
        if (!$request->getPostData()) {
            $response = (new DashboardController())->index($request);
        } else {
            $response = (new ProductController())->createNewProduct($request);
        }
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



