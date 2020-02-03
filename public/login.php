<?php

require_once __DIR__ . '/../bootstrap.php';

use Demoshop\Controllers\LoginController;
use Demoshop\HTTP\Request;

$loginController = new LoginController();
$request = new Request();

if (isset($_COOKIE['loggedIn'])) {
    $loginController->loggedIn();
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

