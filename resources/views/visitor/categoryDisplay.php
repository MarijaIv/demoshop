<?php
/**
 * @var array $categories
 * @var int $currentPage
 * @var int $numberOfPages
 * @var string $sorting
 * @var string $productsPerPage
 * @var array $products
 * @var bool $searchOrCategory
 * @var array $optionCategories
 * @var string $search
 * @var string $keyword
 * @var string $maxPrice
 * @var string $minPrice
 * @var array $selectedCategory
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/visitor/categoryMenu.css">
    <link rel="stylesheet" href="/css/visitor/landing.css">
    <link rel="stylesheet" href="/css/visitor/categoryDisplay.css">
    <link rel="stylesheet" href="/css/visitor/productsDisplay.css">
    <link rel="stylesheet" href="/css/admin/products.css">
    <script type="text/javascript" src="/js/Visitor/menu.js"></script>
    <title>Demoshop</title>
</head>
<body>
<h1>Demo shop</h1>
<div class="menu">
    <div class="menu-links">
        <?php
        require_once __DIR__ . '/categoryMenu.php';
        ?>
    </div>
</div>
<div class="content">
    <div class="products-configurations">
        <?php
        if (!$searchOrCategory) {
            echo '<form id="configuration" method="get" action="/' . $selectedCategory['code'] . '" class="products-display">';
            require_once __DIR__ . '/configuration.php';
            echo '</form>';
        } else {
            echo '<form id="configuration" method="get" action="/search" class="search-configuration" onsubmit="Demoshop.Visitor.Menu.menu.setConfig();">';
            require_once __DIR__ . '/searchCriteria.php';
            require_once __DIR__ . '/configuration.php';
            echo '</form>';
        }
        ?>
    </div>
    <div class="all-products">
        <?php
        if (empty($products) && !$searchOrCategory) {
            echo '<label class="empty-message">' . 'This category is empty.' . '</label>';
        }

        if(empty($products) && $searchOrCategory) {
            echo '<label class="empty-message">' . 'No search results.' . '</label>';
        }

        foreach ($products as $item) {
            echo '<a href="/product/' . $item['sku'] .
                '" class="product-display-link">';
            echo '<div class="product-display"><label class="product-details">' . $item['title'] . '</label>';

            if (!$item['image']) {
                echo '<img src="/img/index.png" alt="Upload image." class="product-display-image" id="imgPlaceHolder">';
            } else {
                echo '<img src="data:image/png;base64,' . $item['image'] .
                    '" alt="Product image." class="product-display-image">';
            }

            echo '<label class="product-details"> Price: ' . $item['price'] . ' rsd</label>
                <label class="product-details">' . $item['shortDescription'] . '</label></div>';
            echo '</a>';
        }
        ?>
    </div>
</div>
</body>

