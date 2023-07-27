<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION["role"]) && $_SESSION["role"] == 'admin') {
    ?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
</head>

<body>

    <header class="top-header">
        <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
    </header>
    <div class="w3-center">
        <a class='w3-bar-item w3-button w3-hover-white' href='admin_users.php'>Benutzer</a>
        <a class='w3-bar-item w3-button w3-hover-white' href='admin_products.php'>Produkte</a>


    </div>
    <div class="w3-row">


        <form class="w3-center" method="POST" id="add_user"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input name="add" value="true" style="display: none">
            <input type="text" name="Username" placeholder="Username">
            <input type="text" name="E-Mail" placeholder="E-Mail">
            <input type="text" name="Passwort" placeholder="Passwort">
            <input type="text" name="Rolle" placeholder="Rolle">
            <button class="w3-btn w3-hide-medium w3-padding-16" type="submit" form="add_user">Add</button>
        </form>
    </div>


    <form class="w3-bar-item w3-right" method="GET" id="search"
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="w3-center">
            <input class="w3-input" type="search" id="suche" name="s" placeholder="Filter Benutzer...">
            <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
                form="search">Suchen</button>
        </div>
    </form>

    <table>
        <tr>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Rolle</th>
            <th>Aktion</th>
        </tr>
        <?php
if (isset($_GET["s"]) && !empty($_GET["s"])) {
        $search_param = sanitize_input($_GET["s"]);
    } else {
        $search_param = "";
    }

    $results = searchUser($search_param);
    foreach ($results as $item) {
        echo '  <tr>
    <td>' . sanitize_input($item["username"]) . '</td>
    <td>' . sanitize_input($item["email"]) . '</td>
    <td>' . sanitize_input($item["role"]) . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_user" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="delete" value="' . sanitize_input($item["username"]) . '" style="display:none">
      <button class="w3-button w3-red">Delete</button>
      </form>
      <form class ="w3-bar-item w3-center" method="POST" id="change_user" action="change_user.php">
      <input class="w3-input" name="change" value="' . sanitize_input($item["username"]) . '" style="display:none">
      <button class="w3-button w3-green">Change</button>
      </form>
</td>
  </tr>';
    }
    ?>
    </table>
</body>

</html>


<?php
// Hinzufügen von Benutzer
    if (isset($_POST["add"])) {
        $unsafe_username = $_POST["Username"];
        $unsafe_email = $_POST["E-Mail"];
        //Wird später gehasht
        $password = $_POST["Passwort"];

        $username = sanitize_input($unsafe_username);
        $email = sanitize_input($unsafe_email);
        $role = sanitize_input($_POST["Rolle"]);

        if ($unsafe_email != $email) {
            $error = "Email contains unallowed characters.";
        }

        if ($unsafe_username != $username) {
            $error = "Username contains unallowed characters.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Incorrect E-Mail format.";
        }

        if (empty($error)) {
            create_user();
            header("Refresh:0");
        } else {
            print($error);
        }
    } // Löschen von Benutzer
    else if (isset($_POST["delete"])) {
        deleteUser(sanitize_input($_POST["delete"]));
        unset($_POST);
        header("Refresh:0");
    }
} else {
    // Kein Admin Seite
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