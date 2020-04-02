<?php

use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidRequestUriOrMethodException;
use Demoshop\AuthorizationMiddleware\Exceptions\RouteAlreadyRegisteredException;
use Demoshop\HTTP\RequestInit;
use Demoshop\Routing\Router;
use Demoshop\Routing\RoutesInit;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

try {
    RoutesInit::routesInit();
} catch (RouteAlreadyRegisteredException $e) {
}

$request = RequestInit::init();

$router = new Router();
try {
    $response = $router->route($request);
    $response->render();
} catch (ControllerOrActionNotFoundException $e) {
    echo "<h1 style=\"color:red\">{$e->getMessage()}</h1>";
} catch (InvalidRequestUriOrMethodException $e) {
    echo "<h1 style=\"color:red\">{$e->getMessage()}</h1>";
}