<?php
/**
 * @var int $amountOfProducts
 * @var int $amountOfCategories
 * @var int $homeViewCount
 * @var int $mostViewedProductId
 * @var string $mostViewedProduct
 * @var int $numberOfMostViews
 *
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/admin/dashboard.css">
    <title>Dashboard</title>
    <meta charset="utf-8">
</head>
<body>
<?php
require_once 'navigation/navigation.php';
?>
<div id="dashboard" class="tabcontent">
    <div class="column">
        <label class="text">Products count: <?php echo $amountOfProducts; ?></label>
        <label class="text">Categories count: <?php echo $amountOfCategories; ?> </label>
    </div>
    <div class="column">
        <label class="text">Home page opening count: <?php echo $homeViewCount; ?></label>
        <label class="text">Most often viewed product: <?php echo $mostViewedProductId; ?></label>
        <label class="text">Number of <?php echo $mostViewedProduct; ?> views: <?php echo $numberOfMostViews; ?></label>
    </div>
</div>
</body>
</html>


