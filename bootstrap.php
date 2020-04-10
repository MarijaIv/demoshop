<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database/config.php';

use Demoshop\AuthorizationMiddleware\Exceptions\ServiceAlreadyRegisteredException;
use Demoshop\Cookie\CookieManager;
use Demoshop\Init\DatabaseInit;
use Demoshop\Repositories\AdminRepository;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Demoshop\Repositories\StatisticsRepository;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Demoshop\Services\CategoryService;
use Demoshop\Services\FrontProductService;
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
    ServiceRegistry::register('CategoryService', static function () {
        return new CategoryService(Demoshop\ServiceRegistry\ServiceRegistry::get('CategoryRepository'));
    });
    ServiceRegistry::register('LoginService', static function () {
        return new LoginService(Demoshop\ServiceRegistry\ServiceRegistry::get('AdminRepository'));
    });
    ServiceRegistry::register('ProductsService', static function () {
        return new ProductService(Demoshop\ServiceRegistry\ServiceRegistry::get('ProductsRepository'));
    });
    ServiceRegistry::register('StatisticsService', static function () {
        return new StatisticsService(Demoshop\ServiceRegistry\ServiceRegistry::get('StatisticsRepository'));
    });
    ServiceRegistry::register('FrontProductService', static function() {
        return new FrontProductService(Demoshop\ServiceRegistry\ServiceRegistry::get('ProductsRepository'));
    });
} catch (ServiceAlreadyRegisteredException $e) {
    echo "<h1 style=\"color:red\">{$e->getMessage()}</h1>";
}

$session = ServiceRegistry::get('Session');
$session->start();