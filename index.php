<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);

include "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="w3.css">
    <link rel="shortcut icon" type="image/ico" href="favicon.ico" />
    <title>This is NUTS</title>
</head>

<body>
    <div class="grid-container">

        <?php
// init suchparameter
if (isset($_GET["s"]) && !empty($_GET["s"])) {
    $search_param = sanitize_input($_GET["s"]);
} else {
    $search_param = "";
}

$results = search($search_param);
foreach ($results as $item) {
    echo '<div class="w3-container w3-center">
      <h3>' . sanitize_input($item['name']) . '</h3>
      <a href=product_details.php?p=' . urlencode(sanitize_input($item['name'])) . '> <img src=' . sanitize_input($item["image_path"]) . ' style="width:50%"></a>
      <div class="w3-section">
        ' . sanitize_input($item["quantity"]) . ' in Stock
        <form class= "w3-bar-item w3-center" method="POST" id="addProductToCart" action="api.php">
        <input name="product_id" value="' . sanitize_input($item['id']) . '" style="display:none">
        <input name="add_to_cart" value="true" style="display:none">
        <input style="width:60px;height:36px" type="number" min=1 name="quantity" value="1">
        <button class="w3-button w3-green">Add to Cart</button>
        </form >
        <a href=product_details.php?p=' . urlencode(sanitize_input($item['name'])) . '>More info</a>
    </div>
</div>';
}
?>
    </div>
</body>