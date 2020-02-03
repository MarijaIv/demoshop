<?php

require_once __DIR__ . '/../bootstrap.php';

use Demoshop\Controllers\LoginController;

$loginController = new LoginController();

if (isset($_COOKIE['loggedIn'])) {
    $loginController->loggedIn();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $loginController->renderLogInPage();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    if ($_POST['keepLoggedIn']) {
        $keepLoggedIn = $_POST['keepLoggedIn'];
    } else {
        $keepLoggedIn = '';
    }

    $loginController->logIn($username, $password, $keepLoggedIn);
}

