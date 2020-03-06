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
<div class="all-products">
    <?php
    foreach ($products as $item) {
        echo '<a href="/index.php?controller=frontProduct&action=index&id=' . $item['id'] . '" class="product-link">';
        echo '<div class="product"><label class="product-details">' . $item['price'] . ' rsd</label>';

        if (!$item['image']) {
            echo '<img src="/img/index.png" alt="Upload image." class="product-image" id="imgPlaceHolder">';
        } else {
            echo '<img src="data:image/png;base64,' . $item['image'] . '" alt="Product image." class="product-image">';
        }

        echo '<label class="product-details">Featured</label>
        <label class="product-details">' . $item['title'] . '</label>
        </div>';
        echo '</a>';
    }
    ?>
</div>
</body>