<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $loggedIn = true;
} else {
    $loggedIn = false;
}

// Hinzufügen des Produktes zum Einkaufswagen
if (strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {
    if (isset($_POST["product_id"])) {
        if (isset($_SESSION["username"])) {
            $product_id = sanitize_input($_POST["product_id"]);
            $quantity = sanitize_input($_POST["quantity"]);
            if (is_numeric($quantity) && $quantity > 0) {
                addToCart($product_id, $quantity);
                header("Location: cart.php");
                exit();
            }
        } else {
            header("Location: login.php", true, 302);
            exit();
        }
    }
}
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
      <a href=product_details.php?p=' . urlencode(sanitize_input($item['name'])) . '> <img src=' . htmlspecialchars($item["image_path"]) . ' style="width:50%"></a>
      <div class="w3-section">
        ' . sanitize_input($item["quantity"]) . ' in Stock
        <form class= "w3-bar-item w3-center" method="POST" id="addProductToCart" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <input name="product_id" value="' . sanitize_input($item['id']) . '" style="display:none">
        <input style="width:60px;height:36px" type="number" min=1 name="quantity" value="1">
        <button class="w3-button w3-green">Add to Cart</button></form >
        <a href=product_details.php?p=' . urlencode(sanitize_input($item['name'])) . '>More info</a>
    </div>
</div>';
}
?>
    </div>
</body>