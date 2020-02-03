<?php


namespace Demoshop\Init;

use Illuminate\Database\Capsule\Manager as Capsule;


class DatabaseInit
{
    public static function init(): void
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => DATABASE,
            'username' => USERNAME,
            'password' => PASSWORD,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
    }

}