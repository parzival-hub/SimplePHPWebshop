<?php
include 'functions.php';
session_start();
error_reporting(0);
if (! isset($_SESSION["username"])) {
    header('Location: login.php', true, 301);
    exit();
}
?>

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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>

<header class="top-header">
   <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
</header>

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
    <th>Aktion</th>
  </tr>
<?php
if (isset($_GET["s"]) && ! empty($_GET["s"]))
    $search_param = sanitize_input($_GET["s"]);
else
    $search_param = "";

$results = searchCart($search_param, $_SESSION['username']);
foreach ($results as $item) {
    echo '  <tr>
    <td>' . $item["name"] . '</td>
    <td>' . $item["description"] . '</td>
    <td>' . $item["quantity"] . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="delete" value="' . $item["name"] . '" style="display:none">
      <button class="w3-button w3-red">Delete</button>
      </form>
</td>
  </tr>';
}

if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    if (isset($_POST["delete"])) {
        $deleteParam = sanitize_input($_POST["delete"]);
        deleteProductCart($deleteParam, $_SESSION["username"]);
        unset($_POST["delete"]);
        header("Refresh:0");
    }
}

?>
</table>