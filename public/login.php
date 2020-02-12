<?php

require_once __DIR__ . '/../bootstrap.php';

use Demoshop\Controllers\FrontControllers\LoginController;

$loginController = new LoginController();

if ($request->getMethod() === 'GET') {
    $response = $loginController->renderLogInPage($request);
} else if ($request->getMethod() === 'POST') {
    $response = $loginController->logIn($request);
}
$response->render();



