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
        foreach (self::$listOfRoutes as $route) {
            if ($route->getHttpMethod() === $request->getMethod() && $route->getPath() === $request->getRequestURI()) {
                return $route;
            }
        }

        return null;
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
}