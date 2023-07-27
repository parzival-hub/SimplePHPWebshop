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
    <link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
    <title>This is NUTS</title>
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto;
  padding: 10px;
}
.grid-item {
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 20px;
  font-size: 30px;
  text-align: center;
}
</style>
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

    <div class= "w3-display-middle w3-wide">
    <h1> Danke für Ihren Einkauf! </h1>
    </div>

   
