<?php


namespace Demoshop\Init;

use Illuminate\Database\Capsule\Manager as Capsule;


/**
 * Class DatabaseInit
 * @package Demoshop\Init
 */
class DatabaseInit
{
    /**
     * Init database.
     *
     * @return void
     */
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