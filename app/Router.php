<?php


namespace Demoshop;


use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
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
     */
    public function route(Request $request): Response
    {
        $controller = '';
        $action = '';
        $controllerName = 'Demoshop\Controllers\AdminControllers\\' .
            ucfirst($request->getGetData()['controller']) . 'Controller';

        if (class_exists($controllerName, true)) {
            $controller = new $controllerName();
            if (is_callable($request->getGetData()['action'], true) && method_exists($controllerName, $request->getGetData()['action'])) {
                $action = $request->getGetData()['action'];

            } else {
                throw new ControllerOrActionNotFoundException();
            }
        }

        if ($controller && $action) {
            return $controller->$action($request);
        }

        return null;
    }
}