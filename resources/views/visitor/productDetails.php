<?php
/**
 * @var array $categories
 * @var array $product
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
    <link rel="stylesheet" href="/css/visitor/productDetails.css">
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
<div class="tab-content">
    <div class="image-column">
        <div class="image-border">
            <?php
            echo '<img src="data:image/png;base64,' . $product['image'] . '" alt="Product image."
            class="product-image" id="imgPlaceHolder">'
            ?>
        </div>
    </div>
    <div class="product-info">
        <?php
        echo '<label class="sku">SKU: <output>' . $product['sku'] . '</output></label>';
        echo '<output class="title">' . $product['title'] . '</output>';
        echo '<textarea class="desc" disabled>' . $product['shortDescription'] . '</textarea>';
        echo '<textarea class="desc" disabled>' . $product['description'] . '</textarea>';
        echo '<label class="price">Price: <output class="price-value">' . $product['price'] . '</output> rsd</label>';
        ?>
    </div>
</div>
</body>