<?php

require_once __DIR__ . '/../bootstrap.php';

use Demoshop\Controllers\LoginController;

$loginController = new LoginController();

if (isset($_COOKIE['loggedIn'])) {
    $loginController->loggedIn();
} else if ($requestInit->getMethod() === 'GET') {
    $loginController->renderLogInPage();
} else if ($requestInit->getMethod() === 'POST') {
    $username = $requestInit->getPostData()['username'];
    $password = md5($requestInit->getPostData()['password']);

    if ($requestInit->getPostData()['keepLoggedIn']) {
        $keepLoggedIn = $requestInit->getPostData()['keepLoggedIn'];
    } else {
        $keepLoggedIn = '';
    }

    $loginController->logIn($username, $password, $keepLoggedIn);
}

