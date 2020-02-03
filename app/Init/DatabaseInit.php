<?php


namespace Demoshop\Init;

use Illuminate\Database\Capsule\Manager as Capsule;


class DatabaseInit
{
    // function for initializing database connection
    public static function init(): void
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => DRIVER,
            'host' => HOST,
            'database' => DATABASE,
            'username' => USERNAME,
            'password' => PASSWORD,
            'charset' => CHARSET,
            'collation' => COLLATION,
            'prefix' => PREFIX,
        ]);

        $capsule->setAsGlobal();
    }

}