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


 <form class="w3-center" method="POST" id="add_product" action="<?php
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    ?>">
    <input name="add" value="true" style="display: none">
	<input  type="text"  name="Name" placeholder="Name">
	<input type="text" name="Description" placeholder="Description">
	<input  type="text"  name="Quantity" placeholder="Quantity">
	<input type="text"  name="Image_Path" placeholder="Image_Path">
	<button class="w3-btn w3-hide-medium w3-padding-16" type="submit" form="add_product">Add</button>

</form>
</div>


 <form class ="w3-bar-item w3-right" method="GET" id="search" action="<?php
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    ?>">
 <div class="w3-center">
	<input class="w3-input" type="search" id="suche" name="s" placeholder="Filter Produkte...">
	<button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit" form="search">Suchen</button>
</div>
</form>

<table>
  <tr>
    <th>Name</th>
    <th>Description</th>
    <th>Quantity</th>
    <th>Image_Path</th>
    <th>Aktion</th>
  </tr>
<?php
    if (isset($_GET["s"]) && ! empty($_GET["s"]))
        $search_param = sanitize_input($_GET["s"]);
    else
        $search_param = "";

    $results = search($search_param);
    foreach ($results as $item) {
        echo '  <tr>
    <td>' . $item["name"] . '</td>
    <td>' . $item["description"] . '</td>
    <td>' . $item["quantity"] . '</td>
    <td>' . $item["image_path"] . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="delete" value="' . $item["name"] . '" style="display:none">
      <button class="w3-button w3-red">Delete</button>
      </form>
</td>
  </tr>';
    }

    /*
     * <a href=admin.php?s=' . urlencode($search_param) . '&delete=' . urlencode($item["name"]) . '>
     * <button class="w3-button w3-red">Delete</button>
     * </a>
     */
    /*
     * <td>
     * <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
     * <input class="w3-input" name="product" value="' . $item["name"] . '" style="display:none">
     * <button class="w3-button w3-red">Delete</button>
     * </form>
     * </td>
     */
    ?>
</table>
</body>
</html>


<?php
    // Hinzuf�gen von Produkten
    if (isset($_POST["add"])) {
        $addParam = sanitize_input($_POST["add"]);
        if ($addParam == "true") {
            $name = sanitize_input($_POST["Name"]);
            $desc = sanitize_input($_POST["Description"]);
            $quantity = sanitize_input($_POST["Quantity"]);
            $image_path = sanitize_input($_POST["Image_Path"]);
            addProduct($name, $desc, $quantity, $image_path);
            unset($_POST["add"]);
            unset($_POST["Name"]);
            unset($_POST["Description"]);
            unset($_POST["Quantity"]);
            unset($_POST["Image_Path"]);
            header("Refresh:0");
        }
    } // L�schen von Produkten
    else if (isset($_POST["delete"])) {
        $deleteParam = sanitize_input($_POST["delete"]);
        deleteProduct($deleteParam);
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