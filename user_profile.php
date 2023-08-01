<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);

if (!isset($_SESSION["username"])) {
    header("Location:login.php");
    exit();
}

if (strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {
    if (isset($_POST["email"])) {
        $email = sanitize_input($_POST["email"]);
        changeUser($_SESSION["user_id"], $email, "email");
        header("Location:" . sanitize_input($_SERVER["PHP_SELF"]));
    } else if (isset($_POST["username"])) {
        $username = sanitize_input($_POST["username"]);
        changeUser($_SESSION["user_id"], $username, "username");
        $_SESSION["username"] = $username;
        header("Location:" . sanitize_input($_SERVER["PHP_SELF"]));
    } else if (isset($_POST["password"])) {
        changeUser($_SESSION["user_id"], $_POST["password"], "password");
    }
}

include "header.php";
?>

<html>
<div style="margin:10px" class="w3-center">
    <table>
        <tr>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Role</th>
        </tr>
        <tr>
            <td id="td_username"><?php echo $_SESSION["username"] ?></td>
            <td id="td_email"><?php
$conn = getConnection();
$sql = "SELECT * FROM `users` WHERE `username` = :searchq";
$query = $conn->prepare($sql);
$query->bindValue("searchq", $_SESSION["username"]);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
echo $row["email"];
?></td>
            <td id="td_role"><?php echo $_SESSION["role"] ?></td>
        </tr>
    </table>
</div>
<div style="margin:10px auto;width:300px">
    <form method="POST" id="username_form">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="New Username">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit">Change</button>
    </form>
    <form method="POST" id="email_form">
        <label for="email">E-Mail:</label>
        <input type="text" name="email" id="email" placeholder="New E-Mail">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit">Change</button>
    </form>
    <form method="POST" id="password_form">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="New Passwort">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit">Change</button>
    </form>
</div>