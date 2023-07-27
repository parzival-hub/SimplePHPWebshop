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

<?php include "header.php";?>

<body>
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

        <div class="w3-display-middle w3-wide">
            <h1> Danke für Ihren Einkauf! </h1>
        </div>