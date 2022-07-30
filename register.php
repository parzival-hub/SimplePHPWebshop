<?php
session_start();

// Redirect zum login
if (array_key_exists('valid', $_SESSION) && $_SESSION["valid"]) {
    header('Location: index.php', true, 301);
    exit();
}

if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    $username = sanitize_input($_POST["username"]);
    $email = sanitize_input($_POST["email"]);
    $password = sanitize_input($_POST["password"]);
    $password2 = sanitize_input($_POST["password2"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
<main>
    <form action="register.php" method="post">
        <h1>Sign Up</h1>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="password2">Password Again:</label>
            <input type="password" name="password2" id="password2">
        </div>
        <div>
            <label for="agree">
                <input type="checkbox" name="agree" id="agree" value="yes"/> I agree
                with the
                <a href="#" title="term of services">term of services</a>
            </label>
        </div>
        <button type="submit">Register</button>
        <footer>Already a member? <a href="login.php">Login here</a></footer>
    </form>
</main>
</body>
</html>


<?php

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>