<?php


namespace Demoshop\Formatters;

use Demoshop\DTO\CategoryDTO;
use Demoshop\Model\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoryFormatter
 * @package Demoshop\Formatters
 */
class CategoryFormatter
{
    /**
     * Format category.
     *
     * @param Category $category
     * @param Category $parent
     * @return array
     */
    public function formatCategory(Category $category, Category $parent = null): array
    {
        $formattedCategory = new CategoryDTO($category);
        $formattedCategory = $formattedCategory->toArray();
        $formattedCategory['parentCode'] = $parent ? $parent->code : '';

        return $formattedCategory;
    }

    /**
     * Format categories for JSON encoding.
     *
     * @param Collection $categories
     * @return array
     */
    public function getFormattedCategories(Collection $categories): array
    {
        $formattedCategories = [];

        foreach ($categories as $item) {
            $formattedCategories[] = new CategoryDTO($item);
        }

        return $formattedCategories;
    }
}