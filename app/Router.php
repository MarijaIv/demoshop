<?php


namespace Demoshop;


use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidControllerOrActionException;
use Demoshop\HTTP\Request;
use Demoshop\HTTP\Response;

/**
 * Class Router
 * @package Demoshop
 */
class Router
{
    /**
     * Determine which controller should be called.
     *
     * @param Request $request
     * @return Response
     * @throws ControllerOrActionNotFoundException
     * @throws InvalidControllerOrActionException
     */
    public function route(Request $request): Response
    {
        if(empty($request->getGetData()['controller']) || empty($request->getGetData()['action'])) {
            throw new InvalidControllerOrActionException();
        }

        $controllerName = ucfirst($request->getGetData()['controller']);
        $action = $request->getGetData()['action'];

        $controllerName = 'Demoshop\Controllers\AdminControllers\\' . $controllerName
            . 'Controller';

        if (!class_exists($controllerName, true)) {
            throw new ControllerOrActionNotFoundException();
        }

        $controller = new $controllerName();

        if (!is_callable($action, true)) {
            throw new ControllerOrActionNotFoundException();
        }

        return $controller->$action($request);

    }
}