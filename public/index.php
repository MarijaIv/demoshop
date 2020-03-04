<?php

use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidControllerOrActionException;
use Demoshop\Controllers\FrontControllers\HomeController;
use Demoshop\Router;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

try {
    if (empty($request->getGetData()['controller'])) {
        $response = (new HomeController())->index($request);
    } else {
        $router = new Router();
        $response = $router->route($request);
    }
} catch (ControllerOrActionNotFoundException $e) {
} catch (InvalidControllerOrActionException $e) {
}

$response->render();
