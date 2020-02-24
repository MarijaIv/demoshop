class ProductsService {

    /**
     * Get all products.
     *
     * @return {Promise}
     */
    listProducts() {
        let ajaxService = new AjaxService();
        return ajaxService.get("/admin.php?controller=product&action=getAllProducts");
    }
}