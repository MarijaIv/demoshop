<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database/config.php';

use Demoshop\AuthorizationMiddleware\Exceptions\ServiceAlreadyRegisteredException;
use Demoshop\Cookie\CookieManager;
use Demoshop\HTTP\RequestInit;
use Demoshop\Init\DatabaseInit;
use Demoshop\Repositories\AdminRepository;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Demoshop\Repositories\StatisticsRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Demoshop\Services\CategoryService;
use Demoshop\Services\LoginService;
use Demoshop\Services\ProductService;
use Demoshop\Services\StatisticsService;
use Demoshop\Session\PHPSession;

DatabaseInit::init();


try {
    ServiceRegistry::register('Session', static function () {
        return new PHPSession();
    });
    ServiceRegistry::register('Cookie', static function () {
        return new CookieManager();
    });
    ServiceRegistry::register('CategoryService', static function () {
        return new CategoryService();
    });
    ServiceRegistry::register('LoginService', static function () {
        return new LoginService();
    });
    ServiceRegistry::register('ProductsService', static function () {
        return new ProductService();
    });
    ServiceRegistry::register('StatisticsService', static function () {
        return new StatisticsService();
    });
    ServiceRegistry::register('AdminRepository', static function () {
        return new AdminRepository();
    });
    ServiceRegistry::register('CategoryRepository', static function () {
        return new CategoryRepository();
    });
    ServiceRegistry::register('ProductsRepository', static function () {
        return new ProductsRepository();
    });
    ServiceRegistry::register('StatisticsRepository', static function () {
        return new StatisticsRepository();
    });
} catch (ServiceAlreadyRegisteredException $e) {
    echo "<h1 style=\"color:red\">{$e->getMessage()}</h1>";
}

$session = ServiceRegistry::get('Session');
$session->start();

$request = RequestInit::init();