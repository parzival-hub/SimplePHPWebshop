<?php
include 'functions.php';
session_start();
error_reporting(0);
//
$error = "";

// Redirect zu index
if (isset($_SESSION["username"])) {
    echo "<script>window.location.assign('index.php');</script>";
    exit();
}

// Bearbeiten des Requests
if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    $unsafe_name = $_POST["username"];
    $pass = $_POST["password"];

    if (empty($unsafe_name) || empty($pass))
        $error = "Alle Felder m�ssen ausgef�llt werden.";

    $name = sanitize_input($unsafe_name);

    if ($name != $unsafe_name)
        $error = "Dieser Name ist nicht erlaubt.";

    if (empty($error)) {
        $loginRole = loginAllowed($name, $pass);
        if ($loginRole != "None")
            login($name, $loginRole);
        else
            $error = "Benutzername und Passwort stimmen nicht �berein.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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