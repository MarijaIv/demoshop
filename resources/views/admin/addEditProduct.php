<?php
/**
 * @var int $id
 * @var array $categories
 * @var string $message
 * @var string $sku
 * @var string $title
 * @var string $brand
 * @var string $category
 * @var float $price
 * @var string $shortDescription
 * @var string $description
 * @var bool $enabled
 * @var bool $featured
 * @var array $image
 *
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/admin/dashboard.css">
    <link rel="stylesheet" href="/css/admin/products.css">
    <link rel="stylesheet" href="/css/admin/addEditProduct.css">
    <script type="text/javascript" src="/js/Category/messages.js"></script>
    <script type="text/javascript" src="/js/Products/addEditProduct.js"></script>
    <title>Products</title>
</head>
<body>
<div class="products">
    <?php
    require 'navigation/navigation.php';
    ?>
    <div class="products-content">
        <h2 class="products-header">Product details</h2>
        <output class="message"><?php echo $message; ?></output>
        <form <?php if (!empty($sku)) {
            echo 'action="/admin/products/' . $sku . '"';
        } else {
            echo 'action="/admin/products/create"';
        } ?> method="post" class="products-form"
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
                    <a href="/admin/products" class="cancel-btn">Cancel</a>
                </div>
                <div class="product-inputs">
                    <input type="text" class="details" id="sku" name="sku" required value="<?php echo $sku; ?>">
                    <input type="text" class="details" id="title" name="title" required value="<?php echo $title; ?>">
                    <input type="text" class="details" id="brand" name="brand" required value="<?php echo $brand; ?>">
                    <select id="category" class="details" name="category">
                        <?php
                        foreach ($categories as $item) {
                            if ($category === $item->id) {
                                echo '<option selected value="' . $item->id
                                    . '" >' . $item->title . '</option>';
                            } else {
                                echo '<option value="' . $item->id
                                    . '" >' . $item->title . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <input type="text" class="details" id="price" name="price" required value="<?php echo $price; ?>">
                    <textarea class="details-ta" id="shortDesc" name="shortDesc"
                              required><?php echo $shortDescription; ?></textarea>
                    <textarea class="details-ta" id="description" name="description"
                              required><?php echo $description; ?></textarea>
                    <?php if ($enabled) {
                        echo '<input type="checkbox" class="enable-feature" id="enabled"
                           name="enabled" checked>';
                    } else {
                        echo '<input type="checkbox" class="enable-feature" id="enabled"
                           name="enabled">';
                    } ?>
                    <?php if ($featured) {
                        echo '<input type="checkbox" class="enable-feature" id="featured"
                           name="featured" checked>';
                    } else {
                        echo '<input type="checkbox" class="enable-feature" id="featured"
                           name="featured">';
                    } ?>
                    <input type="submit" value="OK" class="upload-btn">
                </div>
            </div>
            <div class="product-inputs">
                <label class="details-names">
                    Image:
                    <?php if (!$image) {
                        echo '<img src="/img/index.png" alt="Upload image." class="image" id="imgPlaceHolder">';
                    } else {
                        echo '<img src="data:image/png;base64,' . $image . '" alt="Product image." 
                        class="product-image" id="imgPlaceHolder">';
                    }
                    ?>
                </label>
                <input type="file" id="img" name="img" accept="image/*"
                       onchange="addEditProduct.loadImage(this);" class="custom-file-input" <?php if (!$oldSku) {
                    echo 'required';
                } ?>>
            </div>
        </form>
    </div>
</div>
</body>