<?php


namespace Demoshop\Routing;


use Demoshop\AuthorizationMiddleware\Exceptions\ControllerOrActionNotFoundException;
use Demoshop\AuthorizationMiddleware\Exceptions\InvalidRequestUriOrMethodException;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\Request;
use Demoshop\HTTP\Response;

class Router
{
    /**
     * Find the Route that belongs to the incoming request.
     * From retrieved route get controller and action method.
     * Pass incoming request and any path parameters to the controller's method.
     *
     * @param Request $request
     * @return Response
     * @throws ControllerOrActionNotFoundException
     * @throws InvalidRequestUriOrMethodException
     */
    public function route(Request $request): Response
    {
        if (empty($request->getRequestURI()) || empty($request->getMethod())) {
            throw new InvalidRequestUriOrMethodException();
        }

        $route = Routes::get($request);
        if (!$route) {
            $response = new HTMLResponse($request->getRequestURI());
            $response->setStatus(404);

            return $response;
        }

        $middlewareList = $route->getMiddlewareList();

        foreach ($middlewareList as $middleware) {
            $methods = get_class_methods($middleware);

            foreach ($methods as $method) {
                $middleware::$method($request);
            }
        }

        $controllerName = ucfirst($route->getController());
        $action = $route->getAction();

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

        $uriSegments = explode('/', parse_url($request->getRequestURI(), PHP_URL_PATH));
        $routeSegments = explode('/', $route->getPath());
        $pathSegments = array_combine($routeSegments, $uriSegments);
        $params = [];

        foreach ($pathSegments as $routeSegment => $uriSegment) {
            if ($routeSegment === '%') {
                $params[] = $uriSegment;
            }
        }

        if ($params) {
            return $controller->$action($request, ...$params);
        }

        return $controller->$action($request);
    }
}