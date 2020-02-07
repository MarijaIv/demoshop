<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Category;

class CategoryRepository
{
    /**
     * Get total amount of categories.
     *
     * @return int
     */
    public function getAmountOfCategories(): int
    {
        return Category::query()->count();
    }

}