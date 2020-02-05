<?php

use Demoshop\Model\{Product, Category, Statistic};

require_once 'defaultComponents.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        <?php require_once __DIR__ . '/../../../public/css/dashboard.css'; ?>
    </style>
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
        <output><?php Product::getAmountOfProducts(); ?></output>
        <br/><br/>
        <label>Categories count: </label>
        <output><?php Category::getAmountOfCategories(); ?></output>
    </div>
    <div class="column">
        <label>Home page opening count: </label>
        <output><?php Statistic::getTotalHomeViewCount(); ?></output>
        <br/><br/>
        <label>Most often viewed product: </label>
        <output><?php Product::getMostViewedProduct(); ?></output>
        <br/><br/>
        <label>Number of <?php Product::getMostViewedProduct(); ?> views: </label>
        <output><?php Product::getNumberOfMostViews(); ?></output>
    </div>
</div>
</body>
</html>


