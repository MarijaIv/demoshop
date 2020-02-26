<?php
/**
 * @var array $categories
 * @var string $message
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/products.css">
    <link rel="stylesheet" href="/css/addEditProduct.css">
    <script type="text/javascript" src="/js/ProductsService/ProductsService.js"></script>
    <script type="text/javascript" src="/js/Ajax/AjaxService.js"></script>
    <script type="text/javascript" src="/js/Category/messages.js"></script>
    <script type="text/javascript" src="/js/Products/addEditProduct.js"></script>
    <title>Products</title>
    <meta charset="utf-8">
</head>
<body>
<div class="products">
    <?php
    require 'navigation/navigation.php';
    ?>
    <div class="products-content">
        <h2 class="products-header">Product details</h2>
        <output class="message"><?php echo $message; ?></output>
        <form action="/admin.php" method="post" class="products-form"
              id="addEditTab" enctype="multipart/form-data">
            <div class="product-columns">
                <div class="product-inputs">
                    <label class="details-names" for="sku">SKU:</label>
                    <label class="details-names" for="title">Title:</label>
                    <label class="details-names" for="brand">Brand:</label>
                    <label class="details-names" for="category">Category:</label>
                    <label class="details-names" for="price">Price:</label>
                    <label class="description" for="shortDesc">Short description:</label>
                    <label class="description" for="description">Description:</label>
                    <label class="details-names" for="enabled">Enabled in shop</label>
                    <label class="details-names" for="featured">Featured</label>
                    <button class="cancel-btn">
                        <a class="button-link" href="/admin.php?controller=product&action=index">Cancel</a>
                    </button>
                </div>
                <div class="product-inputs">
                    <input type="text" class="details" id="sku" name="sku" required>
                    <input type="text" class="details" id="title" name="title" required>
                    <input type="text" class="details" id="brand" name="brand" required>
                    <select id="category" class="details" name="category">
                        <?php
                        for ($i = 0, $iMax = count($categories); $i < $iMax; $i++) {
                            echo '<option value="' . $categories[$i]['id']
                                . '" >' . $categories[$i]['title'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="text" class="details" id="price" name="price" required>
                    <textarea class="details-ta" id="shortDesc" name="shortDesc" required></textarea>
                    <textarea class="details-ta" id="description" name="description" required></textarea>
                    <input type="checkbox" class="enable-feature" id="enabled" name="enable">
                    <input type="checkbox" class="enable-feature" id="featured" name="feature">
                    <input type="submit" value="OK" class="upload-btn">
                </div>
            </div>
            <div class="product-inputs">
                <label class="details-names">
                    Image:
                    <img src="/img/index.png" alt="Upload image." class="image" id="imgPlaceHolder">
                </label>
                <input type="file" id="img" name="img" accept="image/*"
                       onchange="addEditProduct.loadImage(this);" class="custom-file-input" required>
            </div>
        </form>
    </div>
</div>
</body>