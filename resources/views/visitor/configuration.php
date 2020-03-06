<?php
/**
 * @var array $categories
 * @var int $currentPage
 * @var int $numberOfPages
 * @var string $sorting
 * @var int $productsPerPage
 * @var array $products
 */
?>

<input type="hidden" name="currentPage" value="<?php echo $currentPage; ?>">
<label for="sorting" class="sort-by">Sort by:
    <select id="sorting" name="sorting" class="options" onchange="this.form.submit()">
        <option value="priceDesc" <?php if ($sorting === 'priceDesc') {
            echo 'selected';
        } ?>>Price descending
        </option>
        <option value="priceAsc" <?php if ($sorting === 'priceAsc') {
            echo 'selected';
        } ?>>Price ascending
        </option>
        <option value="titleDesc" <?php if ($sorting === 'titleDesc') {
            echo 'selected';
        } ?>>Title descending
        </option>
        <option value="titleAsc" <?php if ($sorting === 'titleAsc') {
            echo 'selected';
        } ?>>Title ascending
        </option>
        <option value="brandDesc" <?php if ($sorting === 'brandDesc') {
            echo 'selected';
        } ?>>Brand descending
        </option>
        <option value="brandAsc" <?php if ($sorting === 'brandAsc') {
            echo 'selected';
        } ?>>Brand ascending
        </option>
    </select>
</label>
<div class="display-buttons">
    <input type="submit" name="pagination" value="<<" class="page-button">
    <input type="submit" name="pagination" value="<" class="page-button">
    <label><?php echo $currentPage . '/' . $numberOfPages; ?></label>
    <input type="submit" name="pagination" value=">" class="page-button">
    <input type="submit" name="pagination" value=">>" class="page-button">
</div>
<label for="productsPerPage" class="per-page">Products per page:
    <select id="productsPerPage" name="productsPerPage" class="options" onchange="this.form.submit()">
        <option value="5" <?php if ($productsPerPage === '5') {
            echo 'selected';
        } ?>>5
        </option>
        <option value="10" <?php if ($productsPerPage === '10') {
            echo 'selected';
        } ?>>10
        </option>
        <option value="20" <?php if ($productsPerPage === '20') {
            echo 'selected';
        } ?>>20
        </option>
        <option value="30" <?php if ($productsPerPage === '30') {
            echo 'selected';
        } ?>>30
        </option>
        <option value="50" <?php if ($productsPerPage === '50') {
            echo 'selected';
        } ?>>50
        </option>
        <option value="100" <?php if ($productsPerPage === '100') {
            echo 'selected';
        } ?>>100
        </option>
    </select>
</label>