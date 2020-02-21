<?php


namespace Demoshop\ServiceRegistry;


use Demoshop\AuthorizationMiddleware\Exceptions\ServiceAlreadyRegisteredException;

/**
 * Class ServiceRegistry
 * @package Demoshop\ServiceRegistry
 */
class ServiceRegistry
{
    /**
     * @var array
     */
    private static $registeredServices;
    /**
     * @var ServiceRegistry
     */
    private static $serviceRegistry;

    /**
     * ServiceRegistry constructor.
     */
    private function __construct()
    {

    }

    /**
     * Get ServiceRegistry instance.
     *
     * @return ServiceRegistry
     */
    public static function getInstance(): ServiceRegistry
    {
        if (self::$serviceRegistry === null) {
            self::$serviceRegistry = new ServiceRegistry();
        }

        return self::$serviceRegistry;
    }

    /**
     * Register new service.
     *
     * @param string $key
     * @param callable $method
     * @throws ServiceAlreadyRegisteredException
     */
    public static function register(string $key, callable $method): void
    {
        if (isset(self::$registeredServices[$key])) {
            throw new ServiceAlreadyRegisteredException('Service already regitered for ' . $key);
        }

        self::$registeredServices[$key] = $method;
    }

    /**
     * Get registered service for given key.
     *
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        return (self::$registeredServices[$key])();
    }
}