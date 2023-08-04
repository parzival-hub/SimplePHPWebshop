<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);

if (!isset($_SESSION["user_id"])) {
    header("Location:login.php");
    exit();
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
            <td id="td_username"><?php echo sanitize_input($_SESSION["username"]) ?></td>
            <td id="td_email"><?php echo sanitize_input(getUserEmail()); ?></td>
            <td id="td_role"><?php echo sanitize_input($_SESSION["role"]) ?></td>
        </tr>
    </table>
</div>
<div style="justify-content:center;margin:10px;display:flex">
    <form action="api.php" method="POST" id="username_form" style="display:flex;margin-right:20px">
        <input class="w3-input" type="text" name="username" id="username" placeholder="New Username">
        <input style="display:none" name="change_user">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit">Change</button>
    </form>
    <form action="api.php" method="POST" id="email_form" style="display:flex;margin-right:20px">
        <input class="w3-input" type="text" name="email" id="email" placeholder="New E-Mail">
        <input style="display:none" name="change_user">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit">Change</button>
    </form>
    <form action="api.php" method="POST" id="password_form" style="display:flex;margin-right:20px">
        <input class="w3-input" type="password" name="password" id="password" placeholder="New Passwort">
        <input style="display:none" name="change_user">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit">Change</button>
    </form>

    <?php if (isset($_GET["error"])) {
    echo sanitize_input($_GET["error"]);
}?>
</div>