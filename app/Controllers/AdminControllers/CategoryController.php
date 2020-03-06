<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\JSONResponse;
use Demoshop\HTTP\Request;

/**
 * Class CategoryController
 * @package Demoshop\Controllers\AdminControllers
 */
class CategoryController extends AdminController
{
    /**
     * Function for rendering category.php page.
     *
     * @param Request $request
     * @return HTMLResponse
     */
    public function index(Request $request): HTMLResponse
    {
        return new HTMLResponse('/views/admin/category.php');
    }


    /**
     * Get formatted categories in json file.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function listCategories(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getRootCategories();
        $categoriesForTreeView = $categoryService->formatCategoriesForTreeView($categories);

        return new JSONResponse($categoriesForTreeView);
    }

    /**
     * List all categories in json file.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function listAllCategories(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $categories = $categoryService->getAllCategories();
        $categories = $categoryService->getFormattedCategories($categories);

        return new JSONResponse($categories);
    }

    /**
     * Get category data for display.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function displayCategory(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $category = $categoryService->getCategoryById($request->getGetData()['id']);
        $category = $categoryService->getFormattedCategory($category);

        return new JSONResponse($category);
    }

    /**
     * Delete category if it doesn't have subcategories or products.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function delete(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();

        $category = $categoryService->deleteCategoryById($request->getGetData()['id']);
        if (!$category) {
            $jsonMessage['message'] = 'Category contains products.';
            $json = new JSONResponse($jsonMessage);
            $json->setStatus(400);
            return $json;
        }

        $categories = $categoryService->getRootCategories();
        $categoriesForTreeView = $categoryService->formatCategoriesForTreeView($categories);
        return new JSONResponse($categoriesForTreeView);
    }

    /**
     * Add new category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function addNewCategory(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();
        $category = json_decode($request->getPostData()['jsonString'], true, 512, JSON_THROW_ON_ERROR);

        if (!$categoryService->addNewCategory($category)) {
            $json = new JSONResponse(['message' => 'Failed to insert new category.']);
            $json->setStatus(400);
            return $json;
        }

        $categories = $categoryService->getRootCategories();
        $categoriesForTreeView = $categoryService->formatCategoriesForTreeView($categories);
        return new JSONResponse($categoriesForTreeView);
    }

    /**
     * Update existing category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function updateCategory(Request $request): JSONResponse
    {
        $categoryService = $this->getCategoryService();

        $category = json_decode($request->getGetData()['jsonString'], true, 512, JSON_THROW_ON_ERROR);
        if (!$categoryService->updateCategory($category)) {
            $json = new JSONResponse(['Failed to update category.']);
            $json->setStatus(400);
            return $json;
        }

        $categories = $categoryService->getRootCategories();
        $categoriesForTreeView = $categoryService->formatCategoriesForTreeView($categories);
        return new JSONResponse($categoriesForTreeView);
    }
}