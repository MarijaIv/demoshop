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

$serviceRegistry = ServiceRegistry::getInstance();

try {
    $serviceRegistry->register('Session', function () {
        return new PHPSession();
    });
} catch (ServiceAlreadyRegisteredException $e) {
}

try {
    $serviceRegistry->register('Cookie', function () {
        return new CookieManager();
    });
} catch (ServiceAlreadyRegisteredException $e) {
}

$session = $serviceRegistry->get('Session');
$session->start();

$request = RequestInit::init();