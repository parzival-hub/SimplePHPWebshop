<?php
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

    if (empty($error) && login($name, $pass)) {
        // echo $name . " " . $pass;
        $_SESSION["username"] = $name;
        $_SESSION["valid"] = true;
        header('Location: index.php', true, 301);
        exit();
    }
}

function login($username, $password)
{
    $pass = hash_hmac("sha512", $password, "FJk!br!5");
    return true;
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
            echo utf8_encode($error);
        ?>
        <section>
            <button type="submit">Login</button>
            <a href="register.php">Register</a>
        </section>
    </form>
</main>
</body>
</html>