<?php
/**
 * @var array $optionCategories
 * @var string $keyword
 * @var string $maxPrice
 * @var string $minPrice
 * @var array $selectedCategory
 * @var string $search
 */
?>

<div class="search-criteria">
    <h2>Search criteria</h2>
    <div class="products-display">
        <input type="hidden" name="search" value="<?php echo $search; ?>">
        <label class="search-criteria-labels">Keyword:
            <input type="text" placeholder="Search..." class="search-criteria-input"
                   name="keyword" value="<?php echo $keyword; ?>">
        </label>
        <label class="search-criteria-labels">Category:
            <select class="search-criteria-input" name="category">
                <?php
                echo '<option value=""> Any </option>';
                foreach ($optionCategories as $category) {
                    echo '<option value="' . $category->id . '"';
                    if ($selectedCategory['id'] === $category->id) {
                        echo ' selected ';
                    }
                    echo '>' . $category->title . '</option>';
                }
                ?>
            </select>
        </label>
        <label class="search-criteria-labels">Max price:
            <input type="text" class="search-criteria-input" name="maxPrice" value="<?php echo $maxPrice; ?>">
        </label>
        <label class="search-criteria-labels">Min price:
            <input type="text" class="search-criteria-input" name="minPrice" value="<?php echo $minPrice; ?>">
        </label>
        <input type="submit" value="&#x1f50d" class="search-ok">
    </div>
</div>
