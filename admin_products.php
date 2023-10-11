<?php
include 'functions.php';
error_reporting(E_ERROR | E_PARSE);

session_start();
if ($_SESSION["role"] !== 'admin') {
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
exit();
}
?>

<html>

<?php include "header.php";?>

<div class="w3-center">
    <a class='w3-bar-item w3-button' href='admin_users.php'>User</a>
    <a class='w3-bar-item w3-gray w3-button' href='admin_products.php'>Products</a>
</div>

<div style="margin:10px;margin-top:60px">
    <div style="display: flex;  justify-content: center;">
        <form action=" api.php" method="POST" enctype="multipart/form-data">
            Select image to upload:
            <input class="w3-input" type="file" name="fileToUpload" id="fileToUpload">
            <input class="w3-input" name="adminProducts" value="true" style="display: none">
            <button class="w3-button w3-green">Upload</button>
            <div style="width:200px">
                <?php
if (isset($_SESSION["error"])) {
    print("<p style='color:red'>" . sanitize_input($_SESSION["error"]) . "</p>");
    unset($_SESSION["error"]);
}

if (isset($_SESSION["uploadSuccess"])) {
    print("<p class='w3-center' style='color:green'>" . sanitize_input($_SESSION["uploadSuccess"]) . "</p>");
    unset($_SESSION["uploadSuccess"]);
}
?></div>
        </form>

        <form style="margin-left:150px" class="w3-center" method="POST" id="add_product" action="api.php">
            <input class="w3-input" name="addProduct" value="true" style="display: none">
            <input class="w3-input" name="adminProducts" value="true" style="display: none">
            <input class="w3-input" type="text" name="name" placeholder="Name">
            <input class="w3-input" type="text" name="description" placeholder="Description">
            <input class="w3-input" type="text" name="quantity" placeholder="Quantity">
            <div style="display:flex">
                <select id="add_image_path" name="image">
                    <?php echo getUploadedFilesOptions("uploads"); ?>
                </select>
                <button id="delete_image_btn" style="width:100%" class="w3-button w3-red">Remove</button>
            </div>
            <button type="submit" style="width:100%" class="w3-button w3-green">Add</button>
            <?php if (isset($_SESSION["adderror"])) {
    print("<p style='color:red'>" . $_SESSION["adderror"] . "</p>");
    unset($_SESSION["adderror"]);
}?>
        </form>
    </div>

    <div style="display:flex;margin-top:60px;" class="w3-right">
        <form action="api.php" method="POST" enctype="multipart/form-data" style="margin:10px">
            <input class="w3-button w3-red" type="submit" value="Restock all products" name="restock">
            <input class="w3-input" name="adminProducts" value="true" style="display: none">
        </form>
        <form method="GET" id="search" action="admin_products.php" style="display:flex">
            <input class="w3-input" type="search" id="suche" name="s" placeholder="Filter products...">
            <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
                form="search">Search</button>
        </form>
    </div>
    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Image_Path</th>
            <th>Aktion</th>
        </tr>
        <?php
if (isset($_GET["s"]) && !empty($_GET["s"])) {
    $search_param = sanitize_input($_GET["s"]);
} else {
    $search_param = "";
}

$results = search($search_param);
foreach ($results as $item) {
    echo '  <tr>
    <td>' . sanitize_input($item["name"]) . '</td>
    <td>' . sanitize_input($item["description"]) . '</td>
    <td>' . sanitize_input($item["quantity"]) . '</td>
    <td>' . sanitize_input($item["image_path"]) . '</td>
<td>
 <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="api.php">
      <input class="w3-input" name="delete" value="' . sanitize_input($item["name"]) . '" style="display:none">
      <input class="w3-input" name="adminProducts" value="true" style="display: none">
      <button class="w3-button w3-red">Delete</button>
      </form>
</td>
  </tr>';
}
?>
    </table>
</div>
</body>



<script>
function sendDeleteAjaxRequest() {
    const path = document.getElementById('add_image_path').value;
    fetch("api.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'delete_image=' + encodeURIComponent(path),
    });
}

document.getElementById('delete_image_btn').addEventListener('click', function(event) {
    event.preventDefault();
    sendDeleteAjaxRequest();
    window.location.reload();
});
</script>

</html>