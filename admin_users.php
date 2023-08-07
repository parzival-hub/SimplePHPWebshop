<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_SESSION["role"]) && $_SESSION["role"] === 'admin') {
    ?>

<html>

<?php include "header.php";?>

<body>
    <div class="w3-center">
        <a class='w3-bar-item w3-button w3-gray' href='admin_users.php'>Benutzer</a>
        <a class='w3-bar-item w3-button' href='admin_products.php'>Produkte</a>
    </div>


    <h3 class="w3-center w3-green">C{V3RyI-mP0Rt-Ant}</h3>
    <div style="margin:10px">
        <div class="w3-row">
            <h4>Add User:</h4>
            <form class="w3-center" method="POST" id="add_user" action="api.php">
                <input class="w3-input" name="addUser" value="true" style="display: none">
                <input class="w3-input" type="text" name="username" placeholder="Username">
                <input class="w3-input" type="text" name="email" placeholder="E-Mail">
                <input class="w3-input" type="password" name="password" placeholder="password">
                <select class="w3-select" name="role" id="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button class="w3-button w3-green" type="submit" form="add_user">Add</button>
            </form>
            <?php if (isset($_SESSION["error"])) {
        print(sanitize_input($_SESSION["error"]));
        unset($_SESSION["error"]);
    }
    ?>
        </div>


        <form class="w3-bar-item w3-right" method="GET" action="admin_users.php">
            <div style="display:flex">
                <input class="w3-input" type="search" name="s" placeholder="Search users...">
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
 <form class ="w3-bar-item w3-right" method="POST" action="api.php">
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