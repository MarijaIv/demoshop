<?php


namespace Demoshop\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Admin
 * @package Demoshop\Model
 *
 * @property int id
 * @property string username
 * @property string password
 */
class Admin extends Model
{
    /**
     * @var string
     */
    protected $table = 'admin';

}