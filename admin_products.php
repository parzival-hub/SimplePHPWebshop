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

// Hinzufügen von Produkten
if (isset($_POST["add"])) {
    $addParam = sanitize_input($_POST["add"]);
    if ($addParam === "true") {
        $name = sanitize_input($_POST["name"]);
        $desc = sanitize_input($_POST["description"]);
        $quantity = sanitize_input($_POST["quantity"]);
        $image_path = basename(sanitize_input($_POST["image"]));
        addProduct($name, $desc, $quantity, "/webshop/uploads/" . $image_path);
        header("Location:" . sanitize_input($_SERVER["PHP_SELF"]));
        exit();
    }
} // Löschen von Produkten
else if (isset($_POST["delete"])) {
    $deleteParam = sanitize_input($_POST["delete"]);
    deleteProduct($deleteParam);
    unset($_POST);
    header("Location:" . sanitize_input($_SERVER["PHP_SELF"]));
    exit();
} else if (isset($_POST["restock"])) {
    restock();
    print("<script>alert('Restocked!')</script>");
} else if (isset($_FILES["fileToUpload"])) {
    if (!file_exists("uploads")) {
        mkdir("uploads", 0700, true);
    }
    $target_dir = "uploads/";
    $uploadFileName = sanitize_input($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . basename($uploadFileName);
    $checkError = uploadValid($_FILES["fileToUpload"]);
    if (empty($checkError)) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<p class='w3-center' style='color:green'>The file " . $uploadFileName . " has been uploaded to /webshop/uploads/" . $uploadFileName . "</p>";
        } else {
            echo "<p style='color:red'>Sorry, there was an error uploading your file.</p>";
        }
    }
} // Löschen von Uploads
else if (isset($_POST["delete_image"])) {
    $image_name = sanitize_input($_POST["delete_image"]);
    deleteImage($image_name);
    header("Location:" . $_SERVER["PHP_SELF"]);
    exit();
}
?>

<html>

<?php include "header.php";?>

<div class="w3-center">
    <a class='w3-bar-item w3-button w3-hover-white' href='admin_users.php'>Benutzer</a>
    <a class='w3-bar-item w3-button w3-hover-white' href='admin_products.php'>Produkte</a>
</div>

<div style="margin:10px;margin-top:30px">
    <div class="w3-row" style="display:flex">
        <form class="w3-center" method="POST" id="add_product"
            action="<?php echo sanitize_input($_SERVER["PHP_SELF"]); ?>">
            <input name="add" value="true" style="display: none">
            <input type="text" name="name" placeholder="Name">
            <input type="text" name="description" placeholder="Description">
            <input type="text" name="quantity" placeholder="Quantity">
            <div style="display:flex">
                <select id="add_image_path" name="image">
                    <?php echo getUploadedFilesOptions("uploads"); ?>
                </select>
                <button class="w3-button w3-red" onclick="sendDeleteAjaxRequest()">Remove</button>
            </div>
            <button class="w3-buttton" type="submit" form="add_product">Add</button>
        </form>

        <div style="margin-left:50px">
            <form action="<?php echo sanitize_input($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <button class="w3-buttton" type="submit">Upload</button>
            </form>
            <?php print("<p style='color:red'>$checkError</p>");?>
            <form style="margin-top:50px" action="<?php echo sanitize_input($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data">
                <input class="w3-red" type="submit" value="Restock all products" name="restock">
            </form>
        </div>
    </div>


    <form class="w3-bar-item w3-right" method="GET" id="search"
        action="<?php echo sanitize_input($_SERVER["PHP_SELF"]); ?>">
        <div class="w3-center">
            <input class="w3-input" type="search" id="suche" name="s" placeholder="Filter Produkte...">
            <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
                form="search">Suchen</button>
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
 <form class ="w3-bar-item w3-right" method="POST" id="delete_product" action="' . sanitize_input($_SERVER["PHP_SELF"]) . '">
      <input class="w3-input" name="delete" value="' . sanitize_input($item["name"]) . '" style="display:none">
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
    // Get the selected value from the <select> element
    const selectElement = document.getElementById('add_image_path');
    const selectedValue = selectElement.value;

    // Make a simple AJAX POST request using the fetch API
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'delete_image=' + encodeURIComponent(selectedValue),
    });
}
</script>

</html>