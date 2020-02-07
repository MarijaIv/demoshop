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


<?php
    require_once 'defaultComponents.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/dashboard.css">
    <title>Dashboard</title>
    <meta charset="utf-8">
</head>
<body>
<div class="title">
    <h1>Hello from dashboard page</h1>
</div>
<br/>
<div id="dashboard" class="tabcontent">
    <div class="column">
        <label>Products count: </label>
        <?php echo $amountOfProducts; ?>
        <br/><br/>
        <label>Categories count: </label>
        <?php echo $amountOfCategories; ?>
    </div>
    <div class="column">
        <label>Home page opening count: </label>
        <?php echo $homeViewCount; ?>
        <br/><br/>
        <label>Most often viewed product: </label>
        <?php echo $mostViewedProductId; ?>
        <br/><br/>
        <label>Number of <?php echo $mostViewedProduct; ?>  views: </label>
        <?php echo $numberOfMostViews; ?>
    </div>
</div>
</body>
</html>


