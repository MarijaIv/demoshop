<?php


namespace Demoshop\Controllers;


use Demoshop\ServiceRegistry\ServiceRegistry;
use Demoshop\Services\CategoryService;
use Demoshop\Services\FrontProductService;
use Demoshop\Services\LoginService;
use Demoshop\Services\ProductService;
use Demoshop\Services\StatisticsService;

/**
 * Class BaseController
 * @package Demoshop\Controllers
 */
abstract class BaseController
{
    /**
     * Get category service.
     *
     * @return CategoryService
     */
    protected function getCategoryService(): CategoryService
    {
        return ServiceRegistry::get('CategoryService');
    }

    /**
     * Get product service.
     *
     * @return ProductService
     */
    protected function getProductService(): ProductService
    {
        return ServiceRegistry::get('ProductsService');
    }

    /**
     * Get login service.
     *
     * @return LoginService
     */
    protected function getLoginService(): LoginService
    {
        return ServiceRegistry::get('LoginService');
    }

    /**
     * Get statistics service.
     *
     * @return StatisticsService
     */
    protected function getStatisticsService(): StatisticsService
    {
        return ServiceRegistry::get('StatisticsService');
    }

    /**
     * Get front product service.
     *
     * @return FrontProductService
     */
    protected function getFrontProductService(): FrontProductService
    {
        return ServiceRegistry::get('FrontProductService');
    }
}