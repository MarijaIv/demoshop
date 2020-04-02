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
            throw new InvalidRequestUriOrMethodException('Invalid request uri or method.');
        }

        $route = Routes::get($request);
        if (!$route) {
            $response = new HTMLResponse($request->getRequestURI());
            $response->setStatus(404);

            return $response;
        }

        $middlewareList = $route->getMiddlewareList();

        foreach ($middlewareList as $middleware) {
            call_user_func($middleware . '::handle', $request);
        }

        $controllerName = $route->getController();

        $controller = new $controllerName();
        $action = $route->getAction();

        if (!is_callable($action, true)) {
            throw new ControllerOrActionNotFoundException('Controller or action not found.');
        }

        if ($route->getActionParams()) {
            return $controller->$action($request, ...$route->getActionParams());
        }

        return $controller->$action($request);
    }
}