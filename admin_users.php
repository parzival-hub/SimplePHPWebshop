<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION["role"]) && $_SESSION["role"] === 'admin') {

    // Hinzufügen von Benutzern
    if (isset($_POST["add"])) {
        $unsafe_username = $_POST["username"];
        $unsafe_email = $_POST["email"];
        //Wird später gehasht
        $password = $_POST["password"];
        $username = sanitize_input($unsafe_username);
        $email = sanitize_input($unsafe_email);
        $role = sanitize_input($_POST["role"]);

        if ($unsafe_email != $email) {
            $error = "Email contains unallowed characters.";
        }

        if ($unsafe_username != $username) {
            $error = "Username contains unallowed characters.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Incorrect E-Mail format.";
        }

        if (!in_array($role, ["user", "admin"])) {
            $error = "Incorrect role.";
        }

        if (empty($error)) {
            create_user($username, $password, $email, $role);
        } else {
            print($error);
        }
    } // Löschen von Benutzer
    else if (isset($_POST["delete_id"])) {
        deleteUser(sanitize_input($_POST["delete_id"]));
    }
    ?>

<html>

<?php include "header.php";?>

<body>
    <div class="w3-center">
        <a class='w3-bar-item w3-button w3-hover-white' href='admin_users.php'>Benutzer</a>
        <a class='w3-bar-item w3-button w3-hover-white' href='admin_products.php'>Produkte</a>
    </div>

    <div style="margin:10px">
        <div class="w3-row">
            <h4>Add User:</h4>
            <form class="w3-center" method="POST" id="add_user"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input class="w3-input" name="add" value="true" style="display: none">
                <input class="w3-input" type="text" name="username" placeholder="Username">
                <input class="w3-input" type="text" name="email" placeholder="E-Mail">
                <input class="w3-input" type="password" name="password" placeholder="password">
                <select class="w3-select" name="role" id="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button class="w3-button" type="submit" form="add_user">Add</button>
            </form>
        </div>


        <form class="w3-bar-item w3-right" method="GET" id="search"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div style="display:flex">
                <input class="w3-input" type="search" id="suche" name="s" placeholder="Search users...">
                <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
                    form="search">Search</button>
            </div>
        </form>

        <table>
            <tr>
                <th>Name</th>
                <th>E-Mail</th>
                <th>Role</th>
            </tr>
            <?php
if (isset($_GET["s"]) && !empty($_GET["s"])) {
        $search_param = sanitize_input($_GET["s"]);
    } else {
        $search_param = "";
    }

    $results = searchUser($search_param);
    foreach ($results as $item) {
        if ($item["active"] === 0) {
            continue;
        }

        echo '  <tr>
    <td>' . sanitize_input($item["username"]) . '</td>
    <td>' . sanitize_input($item["email"]) . '</td>
    <td>' . sanitize_input($item["role"]) . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_user" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="delete_id" value="' . sanitize_input($item["id"]) . '" style="display:none">
      <button class="w3-button w3-red">Delete</button>
      </form>
</td>
  </tr>';
    }
    ?>
        </table>
    </div>
</body>

</html>
<?php
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