<?php

namespace Demoshop\Services;

use Demoshop\Repositories\ProductsRepository;

/**
 * Class ProductService
 */
class ProductService
{
    /**
     * Get total amount of products.
     *
     * @return int
     */
    public static function getAmountOfProducts(): int
    {
        $products = new ProductsRepository();
        return $products->getAmountOfProducts();
    }

    /**
     * Get product which details page is displayed most often.
     *
     * @return string
     */
    public static function getMostViewedProduct(): string
    {
        $products = new ProductsRepository();
        return $products->getMostViewedProduct();
    }

    /**
     * Get number of most viewed product views.
     *
     * @return int
     */
    public static function getNumberOfMostViews(): int
    {
        $products = new ProductsRepository();
        return $products->getNumberOfMostViews();
    }

    /**
     * Get id of most viewed product.
     *
     * @return int
     */
    public static function getMostViewedProductId(): int
    {
        $products = new ProductsRepository();
        return $products->getMostViewedProductId();
    }
}