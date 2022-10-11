<?php
include 'functions.php';
session_start();
error_reporting(0);
if (isset($_SESSION["role"]) && $_SESSION["role"] == 'admin') {
    ?>

<html>

<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<link rel="stylesheet" href="style2.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
  <h1 class= w3-display-middle> Admin Section </h1>
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