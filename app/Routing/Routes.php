<?php


namespace Demoshop\Routing;


use Demoshop\AuthorizationMiddleware\Exceptions\RouteAlreadyRegisteredException;
use Demoshop\HTTP\Request;

/**
 * Class Routes
 * @package Demoshop\Routing
 */
class Routes
{
    /**
     * @var array
     */
    private static $listOfRoutes;

    /**
     * @var Routes
     */
    private static $routes;

    /**
     * Routes constructor.
     */
    private function __construct()
    {
    }

    /**
     * Get Routes instance.
     *
     * @return Routes
     */
    public static function getInstance(): Routes
    {
        if (self::$routes === null) {
            self::$routes = new Routes();
        }

        return self::$routes;
    }

    /**
     * Add route to the list of routes.
     *
     * @param Route $route
     * @throws RouteAlreadyRegisteredException
     */
    public static function add(Route $route): void
    {
        foreach (self::$listOfRoutes as $item) {
            if ($item->getHttpMethod() === $route->getHttpMethod() && $item->getPath() === $route->getPath()) {
                throw new RouteAlreadyRegisteredException
                ('Route already registered for ' . $route->getPath() . ' and ' . $route->getHttpMethod());
            }
        }
        self::$listOfRoutes[] = $route;
    }

    /**
     * Get route for the provided request.
     *
     * @param Request $request
     * @return Route|null
     */
    public static function get(Request $request): ?Route
    {
        /** @var Route $routeForRequest */
        $routeForRequest = null;

        foreach (self::$listOfRoutes as $route) {
            if ($route->getHttpMethod() === $request->getMethod()) {
                $uriSegments = explode('/', parse_url($request->getRequestURI(), PHP_URL_PATH));
                $routeSegments = explode('/', $route->getPath());

                if (count($uriSegments) !== count($routeSegments)) {
                    continue;
                }

                $pathSegments = array_combine($uriSegments, $routeSegments);
                $sameRoutes = true;

                foreach ($pathSegments as $uriSegment => $routeSegment) {
                    if ($uriSegment === $routeSegment || ($routeSegment === '%' && $routeForRequest === null)) {
                        $sameRoutes = true;
                    } else {
                        $sameRoutes = false;
                        break;
                    }
                }

                if ($sameRoutes) {
                    $routeForRequest = $route;
                }
            }
        }

        $uriSegments = explode('/', parse_url($request->getRequestURI(), PHP_URL_PATH));
        $routeSegments = explode('/', $routeForRequest->getPath());
        $pathSegments = array_combine($routeSegments, $uriSegments);
        $params = [];

        foreach ($pathSegments as $routeSegment => $uriSegment) {
            if ($routeSegment === '%') {
                $params[] = $uriSegment;
            }
        }

        $routeForRequest->setActionParams($params);

        return $routeForRequest;
    }
}