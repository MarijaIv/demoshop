<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Product;

/**
 * Class ProductsRepository
 * @package Demoshop\Repositories
 */
class ProductsRepository
{

    /**
     * Get total amount of products.
     *
     * @return int
     */
    public function getAmountOfProducts(): int
    {
        return Product::query()->count();
    }

    /**
     * Get product which details page is displayed most often.
     *
     * @return string
     */
    public function getMostViewedProduct(): string
    {
        $result = Product::query()->orderBy('view_count', 'desc')->first();
        return $result ? $result->title : '';
    }

    /**
     * Get id of most viewed product.
     *
     * @return int
     */
    public function getMostViewedProductId(): int
    {
        $result = Product::query()->orderBy('view_count', 'desc')->first();
        return $result? $result->id : '';
    }

    /**
     * Get number of most viewed product views.
     *
     * @return int
     */
    public function getNumberOfMostViews(): int
    {
        return Product::query()->max('view_count');
    }
}