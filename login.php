<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
//
$error = "";

// Redirect zum login
if (isset($_SESSION["username"])) {
    header('Location: index.php');
    exit();
}

// Bearbeiten des Requests
if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    $unsafe_name = $_POST["username"];
    $pass = $_POST["password"];
    $name = sanitize_input($unsafe_name);

    if ($name != $unsafe_name) {
        $error = "Name contains illegal characters.";
    }

    if (empty($error)) {
        $user = loginAllowed($name, $pass);
        if ($user !== "None") {
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["user_id"] = $user["id"];
            echo "<script>window.location.assign('index.php');</script>";
        } else {
            $error = "Username or password do not match our records.";
        }

    }
}

include "header.php"
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <main>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
if (!empty($error)) {
    echo "<p style=\"color:red\">" . sanitize_input($error) . "</p>";
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