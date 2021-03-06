<?php
/**
 * @var array $categories
 * @var int $currentPage
 * @var int $numberOfPages
 * @var string $sorting
 * @var string $productsPerPage
 * @var array $products
 * @var bool $searchOrCategory
 * @var array $selectedCategory
 * @var string $search
 */
?>

<div class="products-display">
    <input type="hidden" name="currentPage" value="<?php echo $currentPage; ?>">
    <?php
    if ($search !== '') {
        echo '<input type="hidden" name="search" value="' . $search . '">';
    }
    ?>
    <label for="sorting" class="sort-by">Sort by:
        <select id="sorting" name="sorting" class="options" onchange="Demoshop.Visitor.Menu.menu.submitForm()">
            <?php if ($searchOrCategory) {
                echo '<option id="relevance" value="relevance"';
                if ($sorting === 'relevance') {
                    echo ' selected';
                }
                echo '>Relevance</option>';
            }
            ?>
            <option id="priceDesc" value="priceDesc" <?php if ($sorting === 'priceDesc') {
                echo 'selected';
            } ?>>Price descending
            </option>
            <option id="priceAsc" value="priceAsc" <?php if ($sorting === 'priceAsc') {
                echo 'selected';
            } ?>>Price ascending
            </option>
            <option id="titleDesc" value="titleDesc" <?php if ($sorting === 'titleDesc') {
                echo 'selected';
            } ?>>Title descending
            </option>
            <option id="titleAsc" value="titleAsc" <?php if ($sorting === 'titleAsc') {
                echo 'selected';
            } ?>>Title ascending
            </option>
            <option id="brandDesc" value="brandDesc" <?php if ($sorting === 'brandDesc') {
                echo 'selected';
            } ?>>Brand descending
            </option>
            <option id="brandAsc" value="brandAsc" <?php if ($sorting === 'brandAsc') {
                echo 'selected';
            } ?>>Brand ascending
            </option>
        </select>
    </label>
    <div class="display-buttons">
        <button type="submit" name="pagination" value="firstPage" class="page-button"> <<</button>
        <button type="submit" name="pagination" value="prevPage" class="page-button"> <</button>
        <label><?php echo $currentPage . '/' . $numberOfPages; ?></label>
        <button type="submit" name="pagination" value="nextPage" class="page-button"> ></button>
        <button type="submit" name="pagination" value="lastPage" class="page-button"> >></button>
    </div>
    <label for="productsPerPage" class="per-page">Products per page:
        <select id="productsPerPage" name="productsPerPage" class="options" value="<?php echo $productsPerPage; ?>"
                onchange="Demoshop.Visitor.Menu.menu.submitForm()">
            <option id="option5" value="5" <?php if ($productsPerPage === '5') {
                echo 'selected';
            } ?>>5
            </option>
            <option id="option10" value="10" <?php if ($productsPerPage === '10') {
                echo 'selected';
            } ?>>10
            </option>
            <option id="option20" value="20" <?php if ($productsPerPage === '20') {
                echo 'selected';
            } ?>>20
            </option>
            <option id="option30" value="30" <?php if ($productsPerPage === '30') {
                echo 'selected';
            } ?>>30
            </option>
            <option id="option50" value="50" <?php if ($productsPerPage === '50') {
                echo 'selected';
            } ?>>50
            </option>
            <option id="option100" value="100" <?php if ($productsPerPage === '100') {
                echo 'selected';
            } ?>>100
            </option>
        </select>
    </label>
</div>