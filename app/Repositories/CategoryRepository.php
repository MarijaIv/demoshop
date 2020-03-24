<?php


namespace Demoshop\Repositories;


use Demoshop\Model\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * Get all root categories.
     *
     * @return Collection
     */
    public function getRootCategories(): Collection
    {
        return Category::query()->where('parent_id', '=', null)->get();
    }

    /**
     * Get subcategories for category.
     *
     * @param int $id
     * @return Collection
     */
    public function getCategoriesForParent(int $id): Collection
    {
        return Category::query()->where('parent_id', '=', $id)->get();
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
     * @return Category | null
     */
    public function getCategoryById(int $id): ?Category
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
     * @param array $data
     * @return bool
     */
    public function addNewCategory($data): bool
    {
        return Category::query()->insert(
            [
                'parent_id' => $data['parentCategory'],
                'code' => $data['code'],
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        );
    }

    /**
     * Update existing category.
     *
     * @param array $data
     * @return int
     */
    public function updateCategory($data): int
    {
        return Category::query()
            ->where('id', '=', $data['id'])
            ->update(
                [
                    'parent_id' => $data['parentCategory'],
                    'code' => $data['code'],
                    'title' => $data['title'],
                    'description' => $data['description'],
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
     * Get categories where title contains keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function getCategoriesByTitle(string $keyword): Collection
    {
        return Category::query()->where('title', 'like', '%' . $keyword . '%')->get();
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
}