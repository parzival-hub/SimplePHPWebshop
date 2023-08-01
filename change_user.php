<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION["role"]) && $_SESSION["role"] === 'admin') {

    if (strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {
        if (isset($_POST["change"])) {
            if (isset($_POST["email"])) {
                $email = sanitize_input($_POST["email"]);
                $user = sanitize_input($_POST["change"]);
                changeUser($user, $email, "email");
                // header('Location: admin_users.php', true, 301);
            } else if (isset($_POST["username"])) {
                $username = sanitize_input($_POST["username"]);
                $user = sanitize_input($_POST["change"]);
                changeUser($user, $username, "username");
            } else if (isset($_POST["password"])) {
                $user = sanitize_input($_POST["change"]);
                changeUser($user, $_POST["password"], "password");
            }
        }
    }

    ?>

<html>

<?php include "header.php";?>

<div class="w3-center">
    <a class='w3-bar-item w3-button w3-hover-white' href='admin_users.php'>Benutzer</a>
    <a class='w3-bar-item w3-button w3-hover-white' href='admin_products.php'>Produkte</a>


</div>
<div class="w3-center">
    <?php
echo "<h3>Benutzer ändern:  " . $_POST["change"] . "</h3>";
    $user = $_POST["change"];
    ?>
</div>
<div class="w3-center">
    <label for="username">Username:</label>
    <form method="POST" id="username">
        <input type="text" name="username" id="username" placeholder="New Username">
        <input name="change" value="<?php

    echo $user;
    ?>" style="display:none">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit"
            form="username">Change</button>
    </form>
</div>
<div class="w3-center">
    <label for="email">E-Mail:</label>
    <form method="POST" id="email">
        <input type="text" name="email" id="email" placeholder="New E-Mail">
        <input name="change" value="<?php

    echo $user;
    ?>" style="display:none">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit"
            form="email">Change</button>
    </form>
</div>
<div class="w3-center">
    <label for="password">Password:</label>
    <form method="POST" id="password">
        <input type="text" name="password" id="password" placeholder="New Password">
        <input name="change" value="<?php

    echo $user;
    ?>" style="display:none">
        <button class="w3-btn w3-bar-item w3-hide-medium w3-hover-white w3-padding-16" type="submit"
            form="password">Change</button>
    </form>
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