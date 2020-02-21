class CategoryService {

    /**
     *  Delete category.
     *
     * @param {int} id
     *
     * @return Promise
     */
    deleteCategory(id) {
        let ajaxService = new AjaxService();
        return ajaxService.get("/admin.php?controller=category&action=deleteOK&id=" + id);
    }

    /**
     * List categories.
     *
     * @return Promise
     */
    listCategory() {
        let ajaxService = new AjaxService();
        return ajaxService.get("/admin.php?controller=category&action=listCategories");
    }

    /**
     * Display category details.
     *
     * @param {int} id
     *
     * @return Promise
     */
    displayCategory(id) {
        let ajaxService = new AjaxService();
        return ajaxService.get("/admin.php?controller=category&action=displayCategory&id=" + id);
    }

    /**
     * Get all categories.
     *
     * @return Promise
     */
    listAllCategories() {
        let ajaxService = new AjaxService();
        return ajaxService.get("/admin.php?controller=category&action=listAllCategories");
    }

    /**
     * Add new category.
     *
     * @param {string} title
     * @param {string} parentCategory
     * @param {string} code
     * @param {string} description
     *
     * @return Promise
     */
    addNewCategory(title, parentCategory, code, description) {
        let ajaxService = new AjaxService();
        if(parentCategory === "root") {
            parentCategory = "";
        }
        let data = {title: title, parentCategory: parentCategory, code: code, description: description};
        return ajaxService.post("/admin.php?controller=category&action=addNewCategory", data);
    }

    /**
     * Update existing category.
     *
     * @param {int} id
     * @param {string} title
     * @param {string} parentCategory
     * @param {string} code
     * @param {string} description
     *
     * @return Promise
     */
    updateCategory(id, title, parentCategory, code, description) {
        let ajaxService = new AjaxService();
        if(parentCategory === "root") {
            parentCategory = ""
        }
        let data = {id: id, title: title, parentCategory: parentCategory, code: code, description: description};
        return ajaxService.put("/admin.php?controller=category&action=updateCategory", data);
    }
}