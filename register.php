<?php
session_start();
include 'functions.php';
error_reporting(E_ERROR | E_PARSE);

// Redirect zum index
if (isset($_SESSION["role"])) {
    header('Location: index.php', true, 301);
    exit();
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
        <form action="api.php" method="post">
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
if (!empty($_SESSION["error"])) {
    echo "<p>" . sanitize_input($_SESSION["error"]) . "</p>";
}
?>
            </div>
            <button type="submit">Register</button>
            <footer>Already a member? <a href="login.php">Login here</a></footer>
        </form>
    </main>
</body>

</html>