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

    /**
     * Format categories for treeview.
     *
     * @param Collection $categories
     * @return array
     */
    public function formatCategoriesForTreeView(Collection $categories): array
    {
        $visitedCategories = [];
        $tree = [];

        while($category = $categories->shift()) {
            $visitedCategories[$category['id']] = (new CategoryDTO($category))->toArray();

            if ($category['parent_id'] === NULL) {
                $tree[] = &$visitedCategories[$category['id']];
            } else {
                $visitedCategories[$category['parent_id']]['children'][] = &$visitedCategories[$category['id']];
            }
        }

        return $tree;
    }

    /**
     * Get categories codes.
     *
     * @param Collection $collection
     * @return array
     */
    public function getCategoriesCodes(Collection $collection): array
    {
        $codes = [];
        foreach ($collection as $item) {
            $codes[] = $item['code'];
        }

        return $codes;
    }
}