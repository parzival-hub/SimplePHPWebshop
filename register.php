<?php
session_start();
include 'functions.php';

// Redirect zum login
if (array_key_exists('valid', $_SESSION) && $_SESSION["valid"]) {
    header('Location: index.php', true, 301);
    exit();
}
$error = "";
if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {

    $unsafe_username = $_POST["username"];
    $unsafe_email = $_POST["email"];
    $username = sanitize_input($unsafe_username);
    $email = sanitize_input($unsafe_email);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($unsafe_email != $email)
        $error = "Email enth�lt nicht erlaubte Zeichen.";
    if ($unsafe_username != $username)
        $error = "Username enth�lt nicht erlaubte Zeichen.";
    if ($password != $password2)
        $error = "Passw�rter stimmen nicht �berein.";
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Inkorrektes E-Mail Format";
    }
    $creationsql= "CREATE TABLE " . $username . " (name VARCHAR(255) NOT NULL, quantity INT(3), description VARCHAR(500) NOT NULL, image_path VARCHAR(100))";
    if (empty($error)) {
        if (checkUserDatabank($username)){
            $conn = getConnection();
            $conn->exec("INSERT INTO `users`(`username`, `password`, `email`,`role`) VALUES ('" . $username . "','" . hash_hmac("sha512", $password, "FJk!br!5") . "','" . $email . "','user')");
            $conn->exec($creationsql);
            $conn = null;
            login($username,'user');
        }
        else{
            echo '<script type="text/javascript">
        window.onload = function () { alert("Benutzername existiert bereits!"); }
        </script>';

        }
        
    }
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
        <?php
        if (! empty($error))
            echo "<p>" . utf8_encode($error) . "</p>"?>
        </div>
        <button type="submit">Register</button>
        <footer>Already a member? <a href="login.php">Login here</a></footer>
    </form>
</main>
</body>
</html>