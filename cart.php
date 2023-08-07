<?php
include 'functions.php';
session_start();
error_reporting(E_ERROR | E_PARSE);
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php', true, 302);
    exit();
}
?>

<?php include "header.php";?>

<form class="w3-bar-item w3-right" method="GET" id="search" action="cart.php">
    <div class="w3-center" style="margin:10px;display:flex">
        <input class="w3-input" type="search" id="suche" name="s" placeholder="Filter products...">
        <button class="w3-button" type="submit" form="search">Search</button>
    </div>
</form>

<table style="margin:10px">
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Quantity</th>
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
 <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="api.php">
      <input class="w3-input" name="product_id" value="' . sanitize_input($item["id"]) . '" style="display:none">
      <input class="w3-input" name="in_cart" value="true" style="display:none">
      <button class="w3-button w3-red">Remove</button>
      </form>
</td>
  </tr>';
}

?>
</table>
<?php
if (empty($results)) {
    echo "<h3 style='margin-left:10px'>No items in cart</h3>";
} else {
    echo '
  <form class ="w3-bar-item w3-center" method="POST" id="buy" action="api.php">
      <input class="w3-input" name="buy" value="true" style="display:none">
      <input class="w3-input" name="in_cart" value="true" style="display:none">
      <button class="w3-button w3-green w3-center">Place order</button>
      </form>
      ';

}
?>