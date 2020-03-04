<?php
/**
 * @var string $message
 * @var string $failMessage
 * @var array $products
 * @var int $currentPage
 * @var int $numberOfPages
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/css/admin/dashboard.css">
    <link rel="stylesheet" href="/css/admin/products.css">
    <script type="text/javascript" src="/js/Category/messages.js"></script>
    <script type="text/javascript" src="/js/Products/productsTable.js"></script>
    <script type="text/javascript" src="/js/Products/addEditProduct.js"></script>
    <title>Products</title>
</head>
<body>
<div class="products">
    <?php
    require 'navigation/navigation.php';
    ?>
    <div class="products-content" id="tableTab">
        <h2 class="products-header">Products</h2>
        <?php if ($message) {
            echo '<output class="message-success">' . $message . '</output>';
        } else {
            echo '<output class="message-fail">' . $failMessage . '</output>';
        } ?>
        <form action="/admin.php" method="post" id="productsTable" class="products-table">
            <div>
                <input type="submit" value="Add new product" class="products-btn"
                       formaction="/admin.php?controller=product&action=addEditProduct">
                <input type="submit" value="Delete selected" class="products-btn"
                       formaction="/admin.php?controller=product&action=deleteMultiple&currentPage=
                       <?php echo $currentPage; ?>">
                <input type="submit" value="Enable selected" class="products-btn"
                       formaction="/admin.php?controller=product&action=enableSelected&currentPage=
                       <?php echo $currentPage; ?>">
                <input type="submit" value="Disable selected" class="products-btn"
                       formaction="/admin.php?controller=product&action=disableSelected&currentPage=
                       <?php echo $currentPage; ?>">
            </div>
            <table id="products">
                <?php
                echo '<tr>
                        <th>Selected</th>
                        <th class="sortable" onclick="productsTable.sort(1);">Title</th>
                        <th>SKU</th>
                        <th class="sortable" onclick="productsTable.sort(3);">Brand</th>
                        <th class="sortable" onclick="productsTable.sort(4);">Category</th>
                        <th class="sortable" onclick="productsTable.sort(5);">Short description</th>
                        <th class="sortable" onclick="productsTable.sort(6);">Price</th>
                        <th>Enable</th>
                        <th></th>
                        <th></th>
                      </tr>';

                foreach ($products as $product) {
                    echo '<tr>
                        <td><input type="checkbox" name="sku ' . $product['sku'] . '" value="' . $product['sku'] . '">
                        </td>
                        <td>' . $product['title'] . '</td>
                        <td>' . $product['sku'] . '</td>
                        <td>' . $product['brand'] . '</td>
                        <td>' . $product['category'] . '</td>
                        <td>' . $product['shortDesc'] . '</td>
                        <td>' . $product['price'] . '</td>
                        <td>
                            <a href="/admin.php?controller=product&action=enableOrDisableProduct&sku='
                        . $product['sku'] . '&currentPage=' . $currentPage . '">';
                    if ($product['enabled']) {
                        echo '<input type="checkbox" checked="checked">';
                    } else {
                        echo '<input type="checkbox">';
                    }
                    echo '</a>
                        </td>
                        <td>
                            <a href="/admin.php?controller=product&action=addEditProduct&sku=' . $product['sku'] . '">
                                <img class="edit" src="/img/icons8-edit-30.png" alt="edit">
                            </a>
                        </td>
                        <td>
                            <input type="submit" class="delete" 
                            formaction="/admin.php?controller=product&action=deleteProduct&sku=' . $product['sku']
                        . '&currentPage=' . $currentPage . '">
                        </td>
                      </tr>';
                }
                ?>
            </table>
            <ul class="paginator">
                <li>
                    <a class="pagination" href="/admin.php?controller=product&action=firstPage"> << </a>
                </li>
                <li>
                    <a class="pagination"
                       href="/admin.php?controller=product&action=prevPage&currentPage=<?php echo $currentPage; ?>">
                        < </a>
                </li>
                <li>
                    <span class="pagination" id="page"><?php echo $currentPage . '/' . $numberOfPages; ?></span>
                </li>
                <li>
                    <a class="pagination"
                       href="/admin.php?controller=product&action=nextPage&currentPage=<?php echo $currentPage; ?>">
                        > </a>
                </li>
                <li>
                    <a class="pagination" href="/admin.php?controller=product&action=lastPage"> >> </a>
                </li>
            </ul>
        </form>
    </div>
</div>
</body>