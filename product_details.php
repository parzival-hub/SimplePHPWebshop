<?php
include 'functions.php';
session_start();
error_reporting(0);
if (isset($_SESSION['username']) && isset($_SESSION['role']))
    $loggedIn = true;
else
    $loggedIn = false;

/*
 * if (isset($_POST["showProductInfo"])){
 * $itemParam = sanitize_input($_POST["showProductInfo"]);
 * $productInfos = showProductInfo($itemParam);
 * }
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
    <title>This is NUTS</title>
</head>

<body>
<header class="top-header">
    <h1> This is NUTS </h1>
</header>
<div class="w3-row">
        <div class="w3-third" style="margin:4px 0 6px 0">
</div>

    <div class="w3-margin-top w3-wide w3-hide-medium w3-hide-small w3-right">

    </div>
    </div>

    <div class="w3-bar w3-theme w3-large" style="z-index:3;">
    <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
<div class=w3-center>
<?php
$productName = sanitize_input($_GET["p"]);
$productDetails = getProduct($productName);
if ($productDetails != NULL) {
    echo "<h1>" . $productName . "</h1>";
    echo "<p>" . $productDetails["quantity"] . " auf Lager</p>";
    echo "<img src=" . $productDetails["image_path"] . ">";
    echo "<p>" . $productDetails["description"] . "<p>";
} else {
    echo "Fehler: Produkt nicht gefunden";
}
?>
</div>