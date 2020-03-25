<?php


namespace Demoshop\Model;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Statistic
 * @package Demoshop\Model
 *
 * @property int id
 * @property int home_view_count
 *
 */
class Statistic extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'statistics';
}