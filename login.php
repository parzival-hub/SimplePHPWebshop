<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);

// Redirect zum index wenn logged in
if (isset($_SESSION["user_id"])) {
    header('Location: index.php');
    exit();
}

include "header.php"
?>

<head>
    <link rel="stylesheet" href="style.css">
</head>
<!DOCTYPE html>
<html lang="en">

<body>
    <main>
        <form method="post" action="api.php">
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
if (!empty($_SESSION["error"])) {
    echo "<p style=\"color:red\">" . sanitize_input($_SESSION["error"]) . "</p>";
}

?>
            <section>
                <button type="submit">Login</button>
                <a href="register.php">Register</a>
            </section>
        </form>
    </main>
</body>

</html>