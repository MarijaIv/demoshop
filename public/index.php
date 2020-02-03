<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;


$result = Capsule::table('admin')->where('id', '=', 2)->get('username');
echo $result;

