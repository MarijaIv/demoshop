<?php


namespace Demoshop;


use Demoshop\HTTP\Request;

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
     */
    public function route(Request $request): void
    {
        $controller = '';
        $action = '';
        $controllerName = 'Demoshop\Controllers\AdminControllers\\' . ucfirst($request->getGetData()['controller']) . 'Controller';

        if (class_exists($controllerName, true)) {
            $controller = new $controllerName();
            $declaredMethods = get_class_methods($controllerName);
            foreach ($declaredMethods as $method) {
                if (strcmp($method, $request->getGetData()['action']) === 0) {
                    $action = $request->getGetData()['action'];
                }
            }
        }


        if ($controller && $action) {
            $response = $controller->$action($request);
            $response->render();
        }
    }
}