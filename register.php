<?php
session_start();
include 'functions.php';
error_reporting(E_ERROR | E_PARSE);

// Redirect zum login
if (array_key_exists('valid', $_SESSION) && $_SESSION["valid"]) {
    header('Location: index.php', true, 301);
    exit();
}
$error = "";
if (strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {

    $unsafe_username = $_POST["username"];
    $unsafe_email = $_POST["email"];
    //Wird später gehasht
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    $username = sanitize_input($unsafe_username);
    $email = sanitize_input($unsafe_email);
    $role = sanitize_input($_POST["Rolle"]);

    if ($unsafe_email != $email) {
        $error = "Email contains unallowed characters.";
    }

    if ($unsafe_username != $username) {
        $error = "Username contains unallowed characters.";
    }

    if ($password != $password2) {
        $error = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Incorrect E-Mail format.";
    }

    if (empty($error)) {
        create_user($username, $password, $email, "user");
        header("Location:index.php");
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
if (!empty($error)) {
    echo "<p>" . sanitize_input($error) . "</p>";
}
?>
            </div>
            <button type="submit">Register</button>
            <footer>Already a member? <a href="login.php">Login here</a></footer>
        </form>
    </main>
</body>

</html>