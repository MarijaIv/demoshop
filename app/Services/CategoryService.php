<?php

namespace Demoshop\Services;


use Demoshop\Repositories\CategoryRepository;

/**
 * Class CategoryService
 * @package Demoshop\Services
 */
class CategoryService
{
    /**
     * Get total amount of categories.
     *
     * @return int
     */
    public static function getAmountOfCategories(): int
    {
        $category = new CategoryRepository();
        return $category->getAmountOfCategories();
    }

}