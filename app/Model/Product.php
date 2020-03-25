<?php


namespace Demoshop\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package Demoshop\Model
 *
 * @property int id
 * @property int category_id
 * @property string sku
 * @property string title
 * @property string brand
 * @property float price
 * @property string short_description
 * @property string description
 * @property string image
 * @property bool enabled
 * @property bool featured
 * @property int view_count
 *
 */
class Product extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'product';
}