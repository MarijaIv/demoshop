<?php


namespace Demoshop\Model;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class Product
 * @package Demoshop\Model
 */
class Product
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $categoryId;
    /**
     * @var
     */
    private $sku;
    /**
     * @var
     */
    private $title;
    /**
     * @var
     */
    private $brand;
    /**
     * @var
     */
    private $price;
    /**
     * @var
     */
    private $short_description;
    /**
     * @var
     */
    private $description;
    /**
     * @var
     */
    private $image;
    /**
     * @var
     */
    private $enabled;
    /**
     * @var
     */
    private $featured;
    /**
     * @var
     */
    private $view_count;

    /**
     * Get product id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get product category id.
     *
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * Get product sku.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Get product title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get product brand.
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * Get product price.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get product short description.
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->short_description;
    }

    /**
     * Get product description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get product image.
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get if product is enabled.
     *
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     *Get if product is featured.
     *
     * @return bool
     */
    public function getFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Get product view count.
     *
     * @return int
     */
    public function getViewCount(): int
    {
        return $this->view_count;
    }

    /**
     * Get total amount of products.
     *
     * @return void
     */
    public static function getAmountOfProducts(): void
    {
        echo Capsule::table('product')->count();
    }

    /**
     * Get product which details page is displayed most often.
     *
     * @return void
     */
    public static function getMostViewedProduct(): void
    {
        $result = Capsule::table('product')->select('title')->orderBy('view_count', 'desc')->first();
        echo $result->title;
    }

    /**
     * Get number of most viewed product views.
     *
     * @return void
     */
    public static function getNumberOfMostViews(): void
    {
        echo Capsule::table('product')->max('view_count');
    }
}