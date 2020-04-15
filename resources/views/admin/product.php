<?php
/**
 * @var string $message
 * @var array $products
 * @var int $currentPage
 * @var int $numberOfPages
 */

use Demoshop\ServiceRegistry\ServiceRegistry;

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
        <?php
        $session = ServiceRegistry::get('Session');
        echo '<output class="message-fail">' . $session->get('errorMessage') . '</output>';
        $session->remove('errorMessage');
        echo '<output class="message-success">' . $session->get('message') . '</output>';
        $session->remove('message');
        ?>
        <form action="/admin/products" method="post" id="productsTable" class="products-table">
            <div>
                <a href="/admin/products/create" class="products-btn-add"> Add new product</a>
                <input type="submit" value="Delete selected" class="products-btn"
                       formaction="/admin/products/deleteMultiple?currentPage=<?php echo $currentPage; ?>">
                <input type="submit" value="Enable selected" class="products-btn"
                       formaction="/admin/products/enableSelected?currentPage=<?php echo $currentPage; ?>">
                <input type="submit" value="Disable selected" class="products-btn"
                       formaction="/admin/products/disableSelected?currentPage=<?php echo $currentPage; ?>">
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
                        <td>';
                    if ($product['enabled']) {
                        echo '<input type="checkbox" 
                        value="'. $product['sku'] . '" 
                        onchange="productsTable.checkboxSubmit(this.value, 
                        \'/admin/products/disableSelected?currentPage='. $currentPage .'\');" checked="checked">';
                    } else {
                        echo '<input type="checkbox" 
                        value="'. $product['sku'] . '" 
                        onchange="productsTable.checkboxSubmit(this.value, 
                        \'/admin/products/enableSelected?currentPage=' . $currentPage . '\');">';
                    }
                    echo '
                        </td>
                        <td>
                            <a href="/admin/products/' . $product['sku'] . '">
                                <img class="edit" src="/img/icons8-edit-30.png" alt="edit">
                            </a>
                        </td>
                        <td>
                            <input type="submit" class="delete" 
                            formaction="/admin/products/delete?sku=' . $product['sku']
                        . '&currentPage=' . $currentPage . '">
                        </td>
                      </tr>';
                }
                ?>
            </table>
            <ul class="paginator">
                <li>
                    <a class="pagination" href="/admin/products/firstPage"> << </a>
                </li>
                <li>
                    <a class="pagination"
                       href="/admin/products/prevPage?currentPage=<?php echo $currentPage; ?>">
                        < </a>
                </li>
                <li>
                    <span class="pagination" id="page"><?php echo $currentPage . '/' . $numberOfPages; ?></span>
                </li>
                <li>
                    <a class="pagination"
                       href="/admin/products/nextPage?currentPage=<?php echo $currentPage; ?>">
                        > </a>
                </li>
                <li>
                    <a class="pagination" href="/admin/products/lastPage"> >> </a>
                </li>
            </ul>
        </form>
    </div>
</div>
</body>