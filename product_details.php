<?php
include 'functions.php';
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['role']))
    $loggedIn = true;
else
    $loggedIn = false;


include "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <div class="w3-bar w3-theme w3-large" style="z-index:3;">
        <div class=w3-center>
            <?php
$productName = sanitize_input($_GET["p"]);
$productDetails = getProduct($productName);
if ($productDetails != NULL) {
    echo "<h1>" . $productName . "</h1>";
    echo "<p>" . $productDetails["quantity"] . " auf Lager</p>";
    echo "<img src=" . $productDetails["image_path"] . " style='width:30%'>";
    echo "<p>" . $productDetails["description"] . "<p>";
    echo "<a href='index.php'>Back</a>";
} else {
    echo "Fehler: Produkt nicht gefunden";
}
?>
        </div>