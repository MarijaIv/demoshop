<?php

namespace Demoshop\Services;


use Demoshop\Model\Category;
use Demoshop\Repositories\CategoryRepository;
use Demoshop\Repositories\ProductsRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get root categories.
     *
     * @return Builder[]|Collection
     */
    public static function getRootCategories(): Collection
    {
        $category = new CategoryRepository();
        return $category->getRootCategories();
    }

    /**
     * Get category by id.
     *
     * @param int $id
     * @return Model
     */
    public static function getCategoryById(int $id): Model
    {
        $category = new CategoryRepository();
        return $category->getCategoryById($id);
    }

    /**
     * Format array for JSON encoding (for treeview).
     *
     * @param $myArray array
     * @return array
     */
    public static function getFormattedArray($myArray): array
    {
        $formattedArray = [];

        for ($i = 0, $iMax = count($myArray); $i < $iMax; $i++) {
            $formattedArray[$i]['id'] = $myArray[$i]->id;
            $formattedArray[$i]['parentId'] = $myArray[$i]->parent_id;
            $formattedArray[$i]['code'] = $myArray[$i]->code;
            $formattedArray[$i]['title'] = $myArray[$i]->title;
            $formattedArray[$i]['description'] = $myArray[$i]->description;

            $children = self::getCategoriesForParent($myArray[$i]->id);
            $formattedArray[$i]['nodes'] = self::getFormattedArray($children);
        }

        return $formattedArray;
    }

    /**
     * Get subcategories for parent.
     *
     * @param int $id
     * @return Builder[]|Collection
     */
    public static function getCategoriesForParent(int $id): Collection
    {
        $category = new CategoryRepository();
        return $category->getCategoriesForParent($id);
    }

    /**
     * Format categories for JSON encoding.
     *
     * @param $myArray
     * @return array
     */
    public static function getFormattedCategories($myArray): array
    {
        $formattedArray = [];

        for ($i = 0, $iMax = count($myArray); $i < $iMax; $i++) {
            $formattedArray[$i]['id'] = $myArray[$i]->id;
            $formattedArray[$i]['parentId'] = $myArray[$i]->parent_id;
            $formattedArray[$i]['code'] = $myArray[$i]->code;
            $formattedArray[$i]['title'] = $myArray[$i]->title;
            $formattedArray[$i]['description'] = $myArray[$i]->description;
        }

        return $formattedArray;
    }

    /**
     * Format category for JSON encoding.
     *
     * @param Model $category
     * @return array
     */
    public static function getFormattedCategory($category): array
    {
        $formattedCategory['id'] = $category->id;

        if ($category->parent_id) {
            $categoryRepository = new CategoryRepository();
            $parent = $categoryRepository->getCategoryById($category->parent_id);
            $formattedCategory['parentId'] = ($parent ? $parent->code : '');
        } else {
            $formattedCategory['parentId'] = '';
        }

        $formattedCategory['title'] = $category->title;
        $formattedCategory['code'] = $category->code;
        $formattedCategory['description'] = $category->description;

        return $formattedCategory;
    }

    /**
     * Check if category has subcategories or products.
     * CategoryService having subcategories or products can't be deleted.
     *
     * @param int $id
     * @return bool
     */
    public static function deleteOK(int $id): bool
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->getCategoryById($id);
        if ($category) {
            $children = $categoryRepository->getCategoriesForParent($category->id);

            $productRepository = new ProductsRepository();
            $products = $productRepository->getProductsByCategoryId($category->id);

            return !($children->count() || $products->count());
        }

        return false;
    }


    /**
     * Delete category by id.
     *
     * @param int $id
     * @return bool
     */
    public static function deleteCategoryById(int $id): bool
    {
        $categoryRepository = new CategoryRepository();
        return $categoryRepository->deleteCategoryById($id);
    }

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public static function getAllCategories(): Collection
    {
        $categoryRepository = new CategoryRepository();
        return $categoryRepository->getAllCategories();
    }

    /**
     * Add new category.
     *
     * @param array $data
     * @return bool
     */
    public static function addNewCategory($data): bool
    {
        $categoryRepository = new CategoryRepository();

        if ($data['title'] === '' || $data['code'] === '' || $data['description'] === '') {
            return false;
        }

        if ($categoryRepository->categoryExistsCode($data['code'])) {
            return false;
        }

        return $categoryRepository->addNewCategory($data);
    }

    /**
     * Update existing category.
     *
     * @param array $data
     * @return int
     */
    public static function updateCategory($data): int
    {
        $categoryRepository = new CategoryRepository();

        if ($data['title'] === '' || $data['code'] === '' || $data['description'] === '') {
            return false;
        }

        if($categoryRepository->categoryExists($data['id'])) {
            $category = $categoryRepository->getCategoryById($data['id']);

            if ($category &&
                $category->code === $data['code'] &&
                $category->title === $data['title'] &&
                $category->description === $data['description'] &&
                ($category->parent_id === (int)$data['parentCategory'] ||
                    ($data['parentCategory'] === '' && !$category->parent_id))) {
                return true;
            }
        }

        return $categoryRepository->updateCategory($data);
    }
}