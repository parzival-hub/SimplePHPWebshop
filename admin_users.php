<?php
include 'functions.php';
session_start();

if (isset($_SESSION["role"]) && $_SESSION["role"] == 'admin') {
    ?>

<html>

<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<link rel="stylesheet" href="style2.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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


 <form class="w3-center" method="POST" id="add_user" action="<?php
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    ?>">
    <input name="add" value="true" style="display: none">
	<input  type="text"  name="Username" placeholder="Username">
	<input type="text" name="E-Mail" placeholder="E-Mail">
	<input  type="text"  name="Passwort" placeholder="Passwort">
	<input type="text"  name="Rolle" placeholder="Rolle">
	<button class="w3-btn w3-hide-medium w3-padding-16" type="submit" form="add_user">Add</button>

</form>
</div>


 <form class ="w3-bar-item w3-right" method="GET" id="search" action="<?php
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    ?>">
 <div class="w3-center">
	<input class="w3-input" type="search" id="suche" name="s" placeholder="Filter Benutzer...">
	<button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit" form="search">Suchen</button>
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
    if (isset($_GET["s"]) && ! empty($_GET["s"]))
        $search_param = sanitize_input($_GET["s"]);
    else
        $search_param = "";

    $results = searchUser($search_param);
    foreach ($results as $item) {
        echo '  <tr>
    <td>' . $item["username"] . '</td>
    <td>' . $item["email"] . '</td>
    <td>' . $item["role"] . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_user" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="delete" value="' . $item["username"] . '" style="display:none">
      <button class="w3-button w3-red">Delete</button>
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
        $username = sanitize_input($unsafe_username);
        $email = sanitize_input($unsafe_email);
        $password = $_POST["Passwort"];
        $role = $_POST["Rolle"];

        if ($unsafe_email != $email)
            $error = "Email enthält nicht erlaubte Zeichen.";
        if ($unsafe_username != $username)
            $error = "Username enthält nicht erlaubte Zeichen.";
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Inkorrektes E-Mail Format";
    }
    $creationsql= "CREATE TABLE " . $username . " (name VARCHAR(255) NOT NULL, quantity INT(3), description VARCHAR(500) NOT NULL, image_path VARCHAR(100))";
    if (empty($error)) {
        if (checkUserDatabank($username)){
            $conn = getConnection();
            $conn->exec("INSERT INTO `users`(`username`, `password`, `email`,`role`) VALUES ('" . $username . "','" . hash_hmac("sha512", $password, "FJk!br!5") . "','" . $email . "','  $role  ')");
            $conn->exec($creationsql);
            $conn = null;
            echo '<script type="text/javascript">
            window.onload = function () { alert("Benutzer erstellt!"); }
            </script>';
            header("Refresh:0");
        }

        
        
    }
    } // Löschen von Benutzer
    else if (isset($_POST["delete"])) {
        $deleteParam = sanitize_input($_POST["delete"]);
        deleteUser($deleteParam);
        unset($_POST["delete"]);
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
