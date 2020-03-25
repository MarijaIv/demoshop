<?php


namespace Demoshop\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package Demoshop\Model
 *
 * @property int id
 * @property int parent_id
 * @property string code
 * @property string title
 * @property string description
 */
class Category extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'category';
}