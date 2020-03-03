<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\JSONResponse;
use Demoshop\HTTP\Request;
use Demoshop\ServiceRegistry\ServiceRegistry;

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
        $categoryService = ServiceRegistry::get('CategoryService');
        $formattedArray = $categoryService->getFormattedArray();

        return new JSONResponse($formattedArray);
    }

    /**
     * List all categories in json file.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function listAllCategories(Request $request): JSONResponse
    {
        $categoryService = ServiceRegistry::get('CategoryService');
        $myArray = $categoryService->getAllCategories();

        return new JSONResponse($myArray);
    }

    /**
     * Get category data for display.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function displayCategory(Request $request): JSONResponse
    {
        $categoryService = ServiceRegistry::get('CategoryService');
        $data = $categoryService->getCategoryById($request->getGetData()['id']);

        return new JSONResponse($data);
    }

    /**
     * Delete category if it doesn't have subcategories or products.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function delete(Request $request): JSONResponse
    {
        $categoryService = ServiceRegistry::get('CategoryService');

        $result = $categoryService->deleteCategoryById($request->getGetData()['id']);
        if (!$result) {
            $formattedArray['message'] = 'Category contains products.';
            $json = new JSONResponse($formattedArray);
            $json->setStatus(400);
            return $json;
        }

        $formattedArray = $categoryService->getFormattedArray();
        return new JSONResponse($formattedArray);
    }

    /**
     * Add new category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function addNewCategory(Request $request): JSONResponse
    {
        $categoryService = ServiceRegistry::get('CategoryService');
        $v = json_decode($request->getPostData()['jsonString'], true, 512, JSON_THROW_ON_ERROR);

        if (!$categoryService->addNewCategory($v)) {
            $json = new JSONResponse(['Failed to insert new category.']);
            $json->setStatus(400);
            return $json;
        }

        $formattedArray = $categoryService->getFormattedArray();
        return new JSONResponse($formattedArray);
    }

    /**
     * Update existing category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function updateCategory(Request $request): JSONResponse
    {
        $categoryService = ServiceRegistry::get('CategoryService');
        $data = json_decode($request->getGetData()['jsonString'], true, 512, JSON_THROW_ON_ERROR);
        if (!$categoryService->updateCategory($data)) {
            $json = new JSONResponse(['Failed to update category.']);
            $json->setStatus(400);
            return $json;
        }

        $formattedArray = $categoryService->getFormattedArray();
        return new JSONResponse($formattedArray);
    }
}