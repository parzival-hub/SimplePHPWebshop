<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION['username']) && isset($_SESSION['role']))
    $loggedIn = true;
else
    $loggedIn = false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="shortcut icon" type="image/ico" href="favicon.ico" />
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

        <?php

    if ($loggedIn)
        echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='logout.php'>Logout</a>";
    else
        echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='login.php'>Login</a>";

    if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin")
        echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='admin.php'>Admin</a>";

    if ($loggedIn) {
        echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='cart.php'><img src=images/shopping-cart.png width=70% height=70%></a>";
        echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='user_profile.php'><img src=images/user.png width=70% height=70%></a>";
        // echo "<h3>Willkommen, " . $_SESSION["username"] . "</h3>";
    }
    ?>
        <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
            form="searchform">Suchen</button>
        <form class="w3-bar-item w3-right" method="GET" id="searchform" action="<?php
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    ?>">
            <div class="w3-row">
                <input class="w3-input" type="search" id="suche" name="s" placeholder="Suche nach Produkten...">
            </div>
        </form>

    </div>

    <div class="grid-container">

        <?php
    // init suchparameter
    if (isset($_GET["s"]) && ! empty($_GET["s"]))
        $search_param = sanitize_input($_GET["s"]);
    else
        $search_param = "";

    $results = search($search_param);
    foreach ($results as $item) {
        echo '<div class="w3-container w3-center">
      <h3>' . $item['name'] . '</h3>
      <a href=product_details.php?p=' . urlencode($item['name']) . '> <img src=' . $item["image_path"] . ' alt="Produktbild" style="width:50%"></a>
      <div class="w3-section">
        ' . $item["quantity"] . ' auf Lager
        <form class= "w3-bar-item w3-center" method="POST" id="addProductToCart" action="' . sanitize_input($_SERVER["PHP_SELF"]) . '">
        <input name="product" value="' . $item['name'] . '" style="display:none">
        <input style="width:10%;height:36px" type="number" min=1 name="quantity" value="1">
        <button class="w3-button w3-green">Kaufen</button></form >
        <a href=product_details.php?p=' . urlencode($item['name']) . '><button class="w3-button w3-red" style="width:40%; margin-top:5px">Mehr Infos</button></a>

    </div>
</div>';
    }
    ?>



    </div>


    <?php

// HinzufÃ¼gen des Produktes zum Einkaufswagen
if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    if (isset($_POST["product"])) {
        if (isset($_SESSION["username"])) {
            $product = sanitize_input($_POST["product"]);
            $quantity = sanitize_input($_POST["quantity"]);
            if (is_numeric($quantity) && $quantity > 0)
                addToCart($product, $quantity, $_SESSION["username"]);
        } else {
            echo "<script>window.location.assign('login.php');</script>";
            die();
        }
    }
}
?>
    <!-- Hier werdet ihr nichts finden... -->
</body>