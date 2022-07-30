<?php
include 'functions.php';
session_start();
// error_reporting(E_ERROR | E_PARSE);
//
$error = "";

// Redirect zum login
if (array_key_exists('valid', $_SESSION) && $_SESSION["valid"]) {
    header('Location: index.php', true, 301);
    exit();
}

// Bearbeiten des Requests
if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    $unsafe_name = $_POST["username"];
    $pass = $_POST["password"];

    if (empty($unsafe_name) || empty($pass))
        $error = "Alle Felder müssen ausgefüllt werden.";

    $name = sanitize_input($unsafe_name);

    if ($name != $unsafe_name)
        $error = "Dieser Name ist nicht erlaubt.";

    if (empty($error)) {
        if (loginAllowed($name, $pass))
            login($name);
        else
            $error = "Benutzername und Passwort stimmen nicht überein.";
    }
}

function loginAllowed($username, $clear_password)
{
    $password = hash_hmac("sha512", $clear_password, "FJk!br!5");
    $conn = getConnection();

    $sql = "SELECT * FROM `users` WHERE `username`=? AND `password`=?";
    $query = $conn->prepare($sql);
    $query->bindValue(1, $username);
    $query->bindValue(2, $password);
    $query->execute();

    if ($query->rowCount() == 1)
        return true;
    else
        return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
<main>
    <form method="post" action="<?php

    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    ?>">
        <h1>Login</h1>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <?php
        if (! empty($error))
            echo "<p style=\"color:red\">" . utf8_encode($error) . "</p>";
        ?>
        <section>
            <button type="submit">Login</button>
            <a href="register.php">Register</a>
        </section>
    </form>
</main>
</body>
</html>