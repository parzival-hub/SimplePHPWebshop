<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (!isset($_SESSION["username"])) {
    header('Location: login.php', true, 301);
    exit();
}
?>

<?php include "header.php";?>

<form class="w3-bar-item w3-right" method="GET" id="search" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">
    <div class="w3-center" style="margin:10px">
        <input class="w3-input" type="search" id="suche" name="s" placeholder="Filter Produkte...">
        <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
            form="search">Suchen</button>
    </div>
</form>

<table style="margin:10px">
    <tr>
        <th>Name</th>
        <th>Beschreibung</th>
        <th>Anzahl</th>
    </tr>
    <?php
if (isset($_GET["s"]) && !empty($_GET["s"])) {
    $search_param = sanitize_input($_GET["s"]);
} else {
    $search_param = "";
}

$results = searchCart($search_param);
foreach ($results as $item) {
    echo '  <tr>
    <td>' . sanitize_input($item["name"]) . '</td>
    <td>' . sanitize_input($item["description"]) . '</td>
    <td>' . sanitize_input($item["quantity"]) . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="product_id" value="' . $item["id"] . '" style="display:none">
      <button class="w3-button w3-red">Löschen</button>
      </form>
</td>
  </tr>';
}

if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
    if (isset($_POST["product_id"])) {
        $product_id = sanitize_input($_POST["product_id"]);
        deleteProductCart($product_id);
        unset($_POST["delete"]);
        header("Location:" . $_SERVER["PHP_SELF"]);
    } else if (isset($_POST["buy"])) {
        buyCart($_SESSION["username"]);
        header('Location: thanks_for_buying.php', true, 301);
    }
}

?>
</table>
<?php
if (!empty($results)) {
    echo '
  <form class ="w3-bar-item w3-center" method="POST" id="buy" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="buy" value="' . $_SESSION['username'] . '" style="display:none">
      <button class="w3-button w3-green w3-center">Bestellung abschließen</button>
      </form>
      ';

}
?>