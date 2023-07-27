<?php
include 'functions.php';
error_reporting(E_ERROR | E_PARSE);

session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"] == 'admin') {
    ?>

<html>

<?php include "header.php";?>

<div class="w3-center">
    <a class='w3-bar-item w3-button w3-hover-white' href='admin_users.php'>Benutzer</a>
    <a class='w3-bar-item w3-button w3-hover-white' href='admin_products.php'>Produkte</a>
</div>

<div style="margin:10px">
    <div class="w3-row" style="display:flex">
        <form class="w3-center" method="POST" id="add_product"
            action="<?php echo sanitize_input($_SERVER["PHP_SELF"]); ?>">
            <input name="add" value="true" style="display: none">
            <input type="text" name="Name" placeholder="Name">
            <input type="text" name="Description" placeholder="Description">
            <input type="text" name="Quantity" placeholder="Quantity">
            <input type="text" id="add_image_path" name="Image_Path" placeholder="Image_Path">
            <button class="w3-btn w3-hide-medium w3-padding-16" type="submit" form="add_product">Add</button>
        </form>

        <div class="w3-center" style="margin-top: 50px;margin-left:50px">
            <form action="<?php echo sanitize_input($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="fileUpload">
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

</html>



<?php

    // Hinzufügen von Produkten
    if (isset($_POST["add"])) {
        $addParam = sanitize_input($_POST["add"]);
        if ($addParam == "true") {
            $name = sanitize_input($_POST["Name"]);
            $desc = sanitize_input($_POST["Description"]);
            $quantity = sanitize_input($_POST["Quantity"]);
            $image_path = sanitize_input($_POST["Image_Path"]);
            addProduct($name, $desc, $quantity, $image_path);
            unset($_POST);
            header('Location: admin_products.php');
            exit();
        }
    } // Löschen von Produkten
    else if (isset($_POST["delete"])) {
        $deleteParam = sanitize_input($_POST["delete"]);
        deleteProduct($deleteParam);
        unset($_POST);
        header('Location: admin_products.php');
        exit();
    } else if (isset($_POST["fileUpload"])) {
        ?><p class="w3-center" style="color:red">
    <?php
if (!file_exists("uploads")) {
            mkdir("uploads", 0700, true);
        }

        $target_dir = "uploads/";
        $uploadFileName = sanitize_input($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . basename($uploadFileName);
        $uploadOk = true;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (!getimagesize($_FILES["fileToUpload"]["tmp_name"])) {
            echo "File is not an image.";
            $uploadOk = false;
        }

        // Check if file already exists
        $tmp_file_name = str_replace("."+$imageFileType, "", $target_file);
        $number = 1;
        while (file_exists($target_file)) {
            $tmp_file_name = $target_file+"-"+$number;
            $number++;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            echo "Sorry, your file is too large. Only files under 1 MB are allowed.";
            $uploadOk = false;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = false;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<p class='w3-center' style='color:green'>The file " . $uploadFileName . " has been uploaded to /webshop/uploads/" . $uploadFileName . "</p>";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        ?></p><?php
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