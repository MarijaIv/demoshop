<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database/config.php';

use Demoshop\AuthorizationMiddleware\Exceptions\ServiceAlreadyRegisteredException;
use Demoshop\Cookie\CookieManager;
use Demoshop\HTTP\RequestInit;
use Demoshop\Init\DatabaseInit;
use Demoshop\ServiceRegistry\ServiceRegistry;
use Demoshop\Session\PHPSession;

DatabaseInit::init();


try {
    ServiceRegistry::register('Session', static function () {
        return new PHPSession();
    });
    ServiceRegistry::register('Cookie', static function () {
        return new CookieManager();
    });
} catch (ServiceAlreadyRegisteredException $e) {
    echo "<h1 style=\"color:red\">{$e->getMessage()}</h1>";
}

$session = ServiceRegistry::get('Session');
$session->start();

$request = RequestInit::init();