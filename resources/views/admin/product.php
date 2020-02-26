<?php
/**
 * @var string $message
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/products.css">
    <script type="text/javascript" src="/js/ProductsService/ProductsService.js"></script>
    <script type="text/javascript" src="/js/Ajax/AjaxService.js"></script>
    <script type="text/javascript" src="/js/Category/messages.js"></script>
    <script type="text/javascript" src="/js/Products/productsTable.js"></script>
    <script type="text/javascript" src="/js/Products/addEditProduct.js"></script>
    <title>Products</title>
    <meta charset="utf-8">
</head>
<body>
<div class="products">
    <?php
    require 'navigation/navigation.php';
    ?>
    <div class="products-content" id="tableTab">
        <h2 class="products-header">Products</h2>
        <output class="message-success"><?php echo $message; ?></output>
        <div>
            <button class="products-btn">
                <a class="button-link" href="/admin.php?controller=product&action=addEditProduct">Add new product</a>
            </button>
            <button class="products-btn">
                <a class="button-link" href="/admin.php?controller=product&action=deleteSelected">Delete selected</a>
            </button>
            <button class="products-btn">
                <a class="button-link" href="/admin.php?controller=product&action=enableSelected">Enable selected</a>
            </button>
            <button class="products-btn">
                <a class="button-link" href="/admin.php?controller=product&action=disableSelected">Disable selected</a>
            </button>
        </div>
        <div id="productsTable" class="products-table">
            <table id="products">
            </table>
            <div class="paginator">
                <input type="button" value="<<" class="pagination" onclick="productsTable.firstPage();">
                <input type="button" value="<" class="pagination" onclick="productsTable.prevPage();">
                <span id="page"></span>
                <input type="button" value=">" class="pagination" onclick="productsTable.nextPage();">
                <input type="button" value=">>" class="pagination" onclick="productsTable.lastPage(); ">
            </div>
        </div>
    </div>
</div>
</body>