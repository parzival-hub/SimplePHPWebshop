<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION["role"]) && $_SESSION["role"] == 'admin') {
    ?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
</head>

<body>

    <header class="top-header">
        <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
    </header>
    <div class="w3-center">
        <a class='w3-bar-item w3-button w3-hover-white' href='admin_users.php'>Benutzer</a>
        <a class='w3-bar-item w3-button w3-hover-white' href='admin_products.php'>Produkte</a>
    </div>

    <div>
        <h1 class=w3-display-middle> Admin Section </h1>
    </div>

    <?php
} else {
    ?>
    <html>

    <body>
        <main>
            <h1>Access Denied: Not Admin
            </h1>
        </main>
    </body>

    </html>

    <?php
}
?>