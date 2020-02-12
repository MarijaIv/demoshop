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
    private $registeredServices;
    /**
     * @var ServiceRegistry
     */
    private static $serviceRegistry;

    /**
     * ServiceRegistry constructor.
     * @param array $registeredServices
     */
    private function __construct(array $registeredServices = null)
    {
        $this->registeredServices = $registeredServices;
    }

    /**
     * Get ServiceRegistry instance.
     *
     * @return ServiceRegistry
     */
    public static function getInstance(): ServiceRegistry
    {
        if(self::$serviceRegistry === null) {
            self::$serviceRegistry = new ServiceRegistry();
        }

        return self::$serviceRegistry;
    }

    /**
     * Get registered services.
     *
     * @return array
     */
    public function getRegisteredServices(): array
    {
        return $this->registeredServices;
    }

    /**
     * Register new service.
     *
     * @param string $key
     * @param callable $method
     * @throws ServiceAlreadyRegisteredException
     */
    public function register(string $key, callable $method): void
    {
        if(isset($this->registeredServices[$key])) {
            throw new ServiceAlreadyRegisteredException();
        }

        $this->registeredServices[$key] = $method;
    }

    /**
     * Get registered service for given key.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->registeredServices[$key]();
    }
}