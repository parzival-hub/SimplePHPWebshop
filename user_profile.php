<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION["username"])) {
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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="style2.css">
</head>

<body>

<header class="top-header">
   <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
</header>

<div style="margin-bottom: 200px" class="w3-center">
<table>
  <tr>
    <th>Name</th>
    <th>E-Mail</th>
    <th>Rolle</th>
  </tr>
<tr>
	<td id="td_username"><?php

    echo $_SESSION["username"]?></td>
	<td id="td_email"><?php
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `username` = :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $_SESSION["username"]);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    echo $row["email"];
    ?></td>
	<td id="td_role"><?php

    echo $_SESSION["role"]?></td>
</tr>
</table>
</div>
<div class="w3-center">
    <label for="username">Username:</label>
    <form  method="POST" id="username_form" >
    	<input  type="text" name="username" id="username" placeholder= "Neuer Username" >
    	<button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit" form="username_form">Ändern</button>
    </form>
   </div>
  <div class="w3-center">
    <label for="email">E-Mail:</label>
    <form  method="POST" id="email_form" >
    	<input type="text" name="email" id="email" placeholder= "Neue E-Mail" >
    	<button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit" form="email_form">Ändern</button>
    </form>
  </div>
  <div class="w3-center">
    <label for="password">Passwort:</label>
    <form  method="POST" id="password_form" >
    	<input type="text" name="password" id="password" placeholder= "Neues Passwort" >
    	<button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit" form="password_form" >Ändern</button>
	</form>
</div>

<?php

    if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
        if (isset($_POST["email"])) {
            $email = sanitize_input($_POST["email"]);
            changeUser($_SESSION["username"], $email, "email");
            echo "<script>
                    document.getElementById('td_email').innerHTML = '$email';
            </script>";
        } else if (isset($_POST["username"])) {
            $username = sanitize_input($_POST["username"]);
            changeUser($_SESSION["username"], $username, "username");
            $_SESSION["username"] = $username;
            echo "<script>
                    document.getElementById('td_username').innerHTML = '$username';
            </script>";
        } else if (isset($_POST["password"])) {
            changeUser($_SESSION["username"], $_POST["password"], "password");
        }
    }
    ?>

<?php
} else {
    echo "<script>window.location.assign('login.php');</script>";
    exit();
}
?>