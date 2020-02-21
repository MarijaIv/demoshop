<?php


namespace Demoshop\Controllers\AdminControllers;


use Demoshop\Controllers\AdminController;
use Demoshop\HTTP\HTMLResponse;
use Demoshop\HTTP\JSONResponse;
use Demoshop\HTTP\Request;
use Demoshop\Services\CategoryService;

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
        $myArray = CategoryService::getRootCategories();
        $formattedArray = CategoryService::getFormattedArray($myArray);

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
        $myArray = CategoryService::getAllCategories();
        $formattedArray = CategoryService::getFormattedCategories($myArray);

        return new JSONResponse($formattedArray);
    }

    /**
     * Get category data for display.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function displayCategory(Request $request): JSONResponse
    {
        $data = CategoryService::getCategoryById($request->getGetData()['id']);
        $data = CategoryService::getFormattedCategory($data);

        return new JSONResponse($data);
    }

    /**
     * Delete category if it doesn't have subcategories or products.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function deleteOK(Request $request): JSONResponse
    {
        $data = CategoryService::deleteOK($request->getGetData()['id']);

        if ($data) {
            $result = CategoryService::deleteCategoryById($request->getGetData()['id']);
            if ($result) {
                $myArray = CategoryService::getRootCategories();
                $formattedArray = CategoryService::getFormattedArray($myArray);

                return new JSONResponse($formattedArray);
            }
        }
        $formattedArray['message'] = 'Category contains products.';
        $json = new JSONResponse($formattedArray);
        $json->setStatus(400);
        return $json;
    }

    /**
     * Add new category.
     *
     * @param Request $request
     * @return JSONResponse
     */
    public function addNewCategory(Request $request): JSONResponse
    {
        $v = json_decode(stripslashes(file_get_contents('php://input')),
            true, 512, JSON_THROW_ON_ERROR);
        if (CategoryService::addNewCategory($v)) {
            $myArray = CategoryService::getRootCategories();
            $formattedArray = CategoryService::getFormattedArray($myArray);

            return new JSONResponse($formattedArray);
        }

        $json = new JSONResponse(['Failed to insert new category.']);
        $json->setStatus(400);
        return $json;
    }

    public function updateCategory(Request $request): JSONResponse
    {
        $data = json_decode(stripslashes(file_get_contents('php://input')),
            true, 512, JSON_THROW_ON_ERROR);
        if (CategoryService::updateCategory($data)) {
            $myArray = CategoryService::getRootCategories();
            $formattedArray = CategoryService::getFormattedArray($myArray);

            return new JSONResponse($formattedArray);
        }

        $json = new JSONResponse(['Failed to update category.']);
        $json->setStatus(400);
        return $json;
    }
}