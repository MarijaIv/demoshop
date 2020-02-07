<?php

require_once __DIR__ . '/../bootstrap.php';

use Demoshop\Controllers\FrontControllers\LoginController;

$loginController = new LoginController();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $keepLoggedIn = '';

    $loginController->logIn($username, $password, $keepLoggedIn);
} else if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
    $keepLoggedIn = 'on';

    $loginController->logIn($username, $password, $keepLoggedIn);
} else if ($request->getMethod() === 'GET') {
    $loginController->renderLogInPage();
} else if ($request->getMethod() === 'POST') {
    $username = $request->getPostData()['username'];
    $password = md5($request->getPostData()['password']);

    if ($request->getPostData()['keepLoggedIn']) {
        $keepLoggedIn = $request->getPostData()['keepLoggedIn'];
    } else {
        $keepLoggedIn = '';
    }

    $loginController->logIn($username, $password, $keepLoggedIn);
}

