<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database/config.php';

use Demoshop\HTTP\RequestInit;
use Demoshop\Init\DatabaseInit;

DatabaseInit::init();

$requestInit = RequestInit::init();