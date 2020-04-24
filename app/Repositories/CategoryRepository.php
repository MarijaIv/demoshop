<?php


namespace Demoshop\Repositories;

use Demoshop\Entity\Category as CategoryEntity;
use Demoshop\Model\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoryRepository
 * @package Demoshop\Repositories
 */
class CategoryRepository
{
    /**
     * Get total amount of categories.
     *
     * @return int
     */
    public function getCountOfCategories(): int
    {
        return Category::query()->count();
    }

    /**
     * Check if category has subcategories.
     *
     * @param int $id
     * @return bool
     */
    public function categoryHasSubcategories(int $id): bool
    {
        return Category::query()->where('parent_id', '=', $id)->exists();
    }

    /**
     * Get category by id.
     *
     * @param int $id
     * @return Category
     */
    public function getCategoryById(int $id): Category
    {
        return Category::query()->where('id', '=', $id)->first();
    }

    /**
     * Delete category by id.
     *
     * @param int $id
     * @return int
     */
    public function deleteCategoryById(int $id): int
    {
        return Category::query()->where('id', '=', $id)->delete();
    }

    /**
     * Get all categories.
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Category::query()->get();
    }

    /**
     * Insert new category.
     *
     * @param CategoryEntity $category
     * @return bool
     */
    public function addNewCategory(CategoryEntity $category): bool
    {
        return Category::query()->insert(
            [
                'parent_id' => $category->getParentId(),
                'code' => $category->getCode(),
                'title' => $category->getTitle(),
                'description' => $category->getDescription(),
            ]
        );
    }

    /**
     * Update existing category.
     *
     * @param CategoryEntity $data
     * @return int
     */
    public function updateCategory(CategoryEntity $data): int
    {
        return Category::query()
            ->where('id', '=', $data->getId())
            ->update(
                [
                    'parent_id' => $data->getParentId(),
                    'code' => $data->getCode(),
                    'title' => $data->getTitle(),
                    'description' => $data->getDescription(),
                ]
            );
    }

    /**
     * Check if category with given id exists.
     *
     * @param int $id
     * @return bool
     */
    public function categoryExists(int $id): bool
    {
        return Category::query()->where('id', '=', $id)->exists();
    }

    /**
     * Check if category with given code exists.
     *
     * @param string $code
     * @return bool
     */
    public function categoryExistsCode(string $code): bool
    {
        return Category::query()->where('code', '=', $code)->exists();
    }

    /**
     * Get category by code.
     *
     * @param string $code
     * @return Category|null
     */
    public function getCategoryByCode(string $code): ?Category
    {
        return Category::query()->where('code', '=', $code)->first();
    }

    /**
     * Get subcategories by code.
     *
     * @param string $code
     * @return Collection
     */
    public function getSubcategoriesByCode(string $code): Collection
    {
        return Category::query()
            ->join('category as parent', 'category.parent_id', '=', 'parent.id')
            ->where('parent.code', '=', $code)
            ->get(['category.code']);
    }
}