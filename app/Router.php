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
        $getData = $request->getGetData();
        $postData = $request->getPostData();

        if ((empty($getData) && (empty($getData['controller']) || empty($getData['action'])))
            && (empty($postData) && (empty($postData()['controller']) || empty($postData()['action'])))) {
            throw new InvalidControllerOrActionException();
        }

        $controllerName = $getData ? ucfirst($getData['controller']) : ucfirst($postData['controller']);
        $action = $getData ? $getData['action'] : $postData['action'];

        $adminControllerName = 'Demoshop\Controllers\AdminControllers\\' . $controllerName
            . 'Controller';

        $frontControllerName = 'Demoshop\Controllers\FrontControllers\\' . $controllerName
            . 'Controller';

        if (!class_exists($adminControllerName, true) && !class_exists($frontControllerName, true)) {
            throw new ControllerOrActionNotFoundException();
        }

        $controller = class_exists($adminControllerName, true) ?
            new $adminControllerName() : new $frontControllerName();

        if (!is_callable($action, true)) {
            throw new ControllerOrActionNotFoundException();
        }

        return $controller->$action($request);
    }
}