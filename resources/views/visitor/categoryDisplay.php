<?php
/**
 * @var array $categories
 * @var array $products
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/visitor/categoryMenu.css">
    <link rel="stylesheet" href="/css/visitor/landing.css">
    <link rel="stylesheet" href="/css/visitor/categoryDisplay.css">
    <script type="text/javascript" src="/js/Visitor/menu.js"></script>
    <title>Demoshop</title>
</head>
<body>
<h1>Demo shop</h1>
<div class="menu">
    <form class="search-box" action="/index.php" method="get">
        <label for="search"></label>
        <input type="text" id="search" name="search" value="Search product..." class="search-field">
        <input type="hidden" name="controller" value="productSearch">
        <input type="hidden" name="action" value="index">
        <input type="submit" value="OK" class="search-ok">
    </form>
    <div class="menu-links">
        <?php
        require_once __DIR__ . '/categoryMenu.php';
        ?>
    </div>
</div>
<div class="all-products">
    <?php
    if (empty($products)) {
        echo '<label class="empty-message">This category is empty.</label>';
    }

    foreach ($products as $item) {
        echo '<a href="/index.php?controller=frontProduct&action=index&id=' . $item->id . '" class="product-display-link">';
        echo '<div class="product-display"><label class="product-details">' . $item->title . '</label>';

        if (!$item->image) {
            echo '<img src="/img/index.png" alt="Upload image." class="product-display-image" id="imgPlaceHolder">';
        } else {
            echo '<img src="data:image/png;base64,' . $item->image . '" alt="Product image." class="product-display-image">';
        }

        echo '<label class="product-details"> Price: ' . $item->price . ' rsd</label>
        <label class="product-details">' . $item->shortDescription . '</label>
        </div>';
        echo '</a>';
    }
    ?>
</div>
</body>

