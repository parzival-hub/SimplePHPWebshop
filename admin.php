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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>

<header class="top-header">
   <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
</header>


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

<div class="w3-center" style="margin-top: 50px">
<form action="admin.php" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="fileUpload">
</form>

<?php
    if (strtoupper($_SERVER["REQUEST_METHOD"]) == "POST") {
        // Hinzuf�gen von Produkten
        if (isset($_POST["add"])) {
            $addParam = sanitize_input($_POST["add"]);
            if ($addParam == "true") {
                $name = sanitize_input($_POST["Name"]);
                $desc = sanitize_input($_POST["Description"]);
                $quantity = sanitize_input($_POST["Quantity"]);
                $image_path = sanitize_input($_POST["Image_Path"]);
                addProduct($name, $desc, $quantity, $image_path);
                header("Refresh:0");
            }
        } // L�schen von Produkten
        else if (isset($_POST["delete"])) {
            $deleteParam = sanitize_input($_POST["delete"]);
            deleteProduct($deleteParam);
            header("Refresh:0");
        } else if (isset($_POST["fileUpload"])) {

            if (! file_exists("uploads")) {
                mkdir("uploads", 0700, true);
            }

            $target_dir = "uploads/";
            $uploadFileName = sanitize_input($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . basename($uploadFileName);
            $uploadOk = true;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = true;
                } else {
                    echo "File is not an image.";
                    $uploadOk = false;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = false;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
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
                    echo "The file " . $uploadFileName . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
    ?></div>

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