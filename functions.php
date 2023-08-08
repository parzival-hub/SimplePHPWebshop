<?php
error_reporting(E_ERROR | E_PARSE);

#SQLMAP Trigger
if (str_contains($_SERVER['HTTP_USER_AGENT'], "sqlmap")) {
    print("You dont really need SqlMap for this :)");
    http_response_code(403); // Return a forbidden status code
    exit;
}

function sanitize_input($data)
{
    $data = trim($data);

    if (preg_match("/C\{[a-zA-Z0-9]{15}\}/", $data)) {
        $data = preg_replace("/[^a-zA-Z0-9üäöÜÄÖ\s@\.\/\{\}]/", "", $data);
    } else {
        $data = preg_replace("/[^a-zA-Z0-9üäöÜÄÖ\s@\.\/]/", "", $data);
    }
    $data = htmlspecialchars($data);
    return $data;
}

function loginUser($username, $clear_password)
{
    $password = hash_hmac("sha512", $clear_password, "FJk!br!5");
    $conn = getConnection();

    $sql = "SELECT * FROM `users` WHERE `username`=? AND `password`=?";
    $query = $conn->prepare($sql);
    $query->bindValue(1, $username);
    $query->bindValue(2, $password);
    $query->execute();

    if ($query->rowCount() === 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return "None";
    }

}

function solve_challenge($id, $solution)
{
    $conn = getConnection();
    $sql = "SELECT solution FROM challenges where id=:id";
    $query = $conn->prepare($sql);
    $query->bindValue("id", $id);
    $query->execute();
    if ($query->fetchColumn() === $solution) {
        $sql = "UPDATE challenges SET solved=1 where id=:id";
        $query = $conn->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();
    }
}

function reset_challenges()
{
    $conn = getConnection();
    $sql = "UPDATE challenges SET solved=0";
    $query = $conn->prepare($sql);
    $query->execute();
}

function get_challenges()
{
    $conn = getConnection();
    $sql = "SELECT * FROM challenges";
    $query = $conn->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getConnection()
{
    return new PDO('mysql:host=127.0.0.1;dbname=xiks5egieksn6c6a;charset=utf8mb4', 'rm3AER5PkBnnEiTg', 'aS7HFRb94!@t3LTR', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
    ));
}

function deleteProduct($productName)
{
    $conn = getConnection();
    $sql = "DELETE FROM `products` WHERE `name`=:delParam";
    $query = $conn->prepare($sql);
    $query->bindValue("delParam", $productName);
    $query->execute();
}

function getProduct($productName)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` =:searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $productName);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function search($searchParam)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` LIKE :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", "%" . $searchParam . "%");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// function searchUser($searchParam)
// {
//     $conn = getConnection();
//     $sql = "SELECT * FROM `users` WHERE `username` LIKE :searchq";
//     $query = $conn->prepare($sql);
//     $query->bindValue("searchq", "%" . sanitize_input($searchParam) . "%");
//     $query->execute();
//     return $query->fetchAll(PDO::FETCH_ASSOC);
// }

function getUserEmail()
{
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `id` = :user_id";
    $query = $conn->prepare($sql);
    $query->bindValue("user_id", $_SESSION["user_id"]);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    return $row["email"];
}

function addToCart($product_id, $quantity)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `id` = :id";
    $query = $conn->prepare($sql);
    $query->bindValue("id", $product_id);
    $query->execute();
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $sql = "SELECT * FROM cart WHERE `user_id` = :user_id and product_id=:product_id";
        $query = $conn->prepare($sql);
        $query->bindValue("user_id", $_SESSION["user_id"]);
        $query->bindValue("product_id", $product_id);
        $query->execute();

        $product_in_cart = $query->fetch(PDO::FETCH_ASSOC);
        // Falls Produkt nicht vorhanden, füge hinzu
        $avail_quant = getAvailableQuantity($product_id);
        if ($quantity > $avail_quant) {
            $quantity = $avail_quant;
        }
        if (!$product_in_cart) {
            $sql = "INSERT INTO cart (`user_id`, `product_id`, `quantity`) VALUES (:user_id,:product_id,:quantity)";
            $query2 = $conn->prepare($sql);
            $query2->bindValue("user_id", $_SESSION["user_id"]);
            $query2->bindValue("product_id", $product_id);
            $query2->bindValue("quantity", $quantity);
            $query2->execute();
        } // Falls Produkt schon vorhanden, erhöhe um quant
        else {
            $sql = "UPDATE cart SET  quantity = quantity + :quantity WHERE product_id = :product_id and user_id=:user_id";
            $query2 = $conn->prepare($sql);
            $query2->bindValue("product_id", $product_id);
            $query2->bindValue("user_id", $_SESSION["user_id"]);
            $query2->bindValue("quantity", $quantity);
            $query2->execute();
        }
    }
}

function getAvailableQuantity($product_id)
{
    $conn = getConnection();
    $sql = "SELECT SUM(quantity) FROM cart WHERE `product_id` = :product_id";
    $query = $conn->prepare($sql);
    $query->bindValue("product_id", $product_id);
    $query->execute();
    $in_cart = $query->fetchColumn();

    $sql = "SELECT quantity FROM `products` WHERE `id` = :product_id";
    $query = $conn->prepare($sql);
    $query->bindValue("product_id", $product_id);
    $query->execute();
    $max_quantity = $query->fetchColumn();
    return $max_quantity - $in_cart;
}

function searchCart($searchParam)
{
    $conn = getConnection();
    $sql = "SELECT `name`, `description`,cart.quantity,user_id,id  FROM cart JOIN products ON products.id = cart.product_id WHERE `user_id`=:user AND (SELECT `name` FROM products WHERE id=product_id) LIKE :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", "%" . $searchParam . "%");
    $query->bindValue("user", $_SESSION['user_id']);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function deleteProductCart($product_id)
{
    $conn = getConnection();
    $sql = "DELETE FROM cart WHERE user_id=:user_id and product_id=:product_id";
    $query = $conn->prepare($sql);
    $query->bindValue("user_id", $_SESSION["user_id"]);
    $query->bindValue("product_id", $product_id);
    $query->execute();
}

function userExists($userName)
{
    $conn = getConnection();
    $sql = "SELECT * FROM users WHERE `username`=:userParam";
    $query = $conn->prepare($sql);
    $query->bindValue("userParam", $userName);
    $query->execute();
    return !empty($query->fetchAll());
}

function create_user($username, $password, $email, $role)
{
    if (!userExists($username)) {
        $conn = getConnection();
        $query = $conn->prepare("INSERT INTO `users`(`username`, `password`, `email`,`role`) VALUES (:username,:pass,:email,:userRole)");
        $query->bindValue("username", $username);
        $query->bindValue("pass", hash_hmac("sha512", $password, "FJk!br!5"));
        $query->bindValue("email", $email);
        $query->bindValue("userRole", $role);
        $query->execute();
    }
}

function deleteUser($user_id)
{
    $conn = getConnection();
    $sql = "DELETE FROM users WHERE `id`=:user_id";
    $query = $conn->prepare($sql);
    $query->bindValue("user_id", $user_id);
    $query->execute();

    $query = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
    $query->bindValue("user_id", $user_id);
    $query->execute();
}

function changeUser($changeParam, $changePlace)
{
    $conn = getConnection();
    if ($changePlace === "password") {
        $sql = "UPDATE users SET password = :pass WHERE id = :user_id";
        $query = $conn->prepare($sql);
        $query->bindValue("user_id", $_SESSION["user_id"]);
        $query->bindValue("pass", hash_hmac("sha512", $changeParam, "FJk!br!5"));
        $query->execute();
    } else if ($changePlace === "username" || $changePlace === "email") {
        $sql = "UPDATE users SET $changePlace = :changeParam WHERE id = :user_id";
        $query = $conn->prepare($sql);
        $query->bindValue("changeParam", $changeParam);
        $query->bindValue("user_id", $_SESSION["user_id"]);
        $query->execute();
    }
}

function buyCart()
{
    $conn = getConnection();
    $updateSql = "UPDATE products
                  JOIN cart ON products.id = cart.product_id
                  SET products.quantity = products.quantity - cart.quantity
                  WHERE cart.user_id = :user_id";

    $updateQuery = $conn->prepare($updateSql);
    $updateQuery->bindValue(":user_id", $_SESSION["user_id"]);
    $updateQuery->execute();

    $query = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
    $query->bindValue("user_id", $_SESSION["user_id"]);
    $query->execute();
}

function uploadFile($uploadFile)
{
    if (!file_exists("uploads")) {
        mkdir("uploads", 0700, true);
    }
    $target_dir = "uploads/";
    $uploadFileName = sanitize_input($uploadFile["name"]);
    $target_file = $target_dir . basename($uploadFileName);
    $checkError = uploadValid($uploadFile);
    if (empty($checkError)) {
        if (move_uploaded_file($uploadFile["tmp_name"], $target_file)) {
            $_SESSION["uploadSuccess"] = "The file " . $uploadFileName . " has been uploaded to /webshop/uploads/" . $uploadFileName;
            header("Location: admin_products.php");
            exit();
        } else {
            $_SESSION["error"] = "An unknown error happend. Please try again later.";
            header("Location: admin_products.php");
            exit();
        }
    } else {
        $_SESSION["error"] = $checkError;
        header("Location: admin_products.php");
        exit();
    }
}

function uploadValid($myfile)
{
    $target_dir = "uploads/";
    $uploadFileName = sanitize_input($myfile["name"]);
    $target_file = $target_dir . basename($uploadFileName);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (!getimagesize($myfile["tmp_name"])) {
        return "File is not an image.";
    }

    // Check file size
    if ($myfile["size"] > 1000000) {
        return "Sorry, your file is too large. Only files under 1 MB are allowed.";
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    //Check Content Type
    if (!in_array($myfile["type"], ["image/jpg", "image/png", "image/jpeg", "image/gif"])) {
        return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    //Check Image integrity
    if (!check_image_integrity($myfile["tmp_name"])) {
        return "Image did not pass security test.";
    }
}

function check_image_integrity($file)
{
    // Check if the file exists and is an actual file
    if (!is_file($file)) {
        return false; // File not found or not a valid file
    }

    // Check if image file is a valid image
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimetype = finfo_file($finfo, $file);
    finfo_close($finfo);

    // Supported image types: PNG, JPG, GIF
    if ($mimetype !== "image/png" && $mimetype !== "image/jpeg" && $mimetype !== "image/gif") {
        return false; // Invalid image
    }

    // For PNG, JPG, and GIF, perform basic header checks
    if ($mimetype === "image/png") {
        // Check for PNG header
        $header = file_get_contents($file, false, null, 0, 8);
        return strncmp($header, "\x89PNG\x0D\x0A\x1A\x0A", 8) === 0;
    }

    if ($mimetype === "image/jpeg") {
        // Check for JPG header
        $header = file_get_contents($file, false, null, 0, 2);
        return strncmp($header, "\xFF\xD8", 2) === 0;
    }

    if ($mimetype === "image/gif") {
        // Check for GIF header
        $header = file_get_contents($file, false, null, 0, 6);
        return strncmp($header, "GIF87a", 6) === 0 || strncmp($header, "GIF89a", 6) === 0;
    }

    // If we reach here, something went wrong, so return false
    return false;
}

function restock()
{
    $conn = getConnection();
    $query = $conn->prepare("UPDATE products SET quantity = FLOOR(50 + RAND() * 50) WHERE id = :product_id");

    // Retrieve all product IDs to perform updates for each product individually
    $productIdsQuery = $conn->query("SELECT id FROM products");
    $productIds = $productIdsQuery->fetchAll(PDO::FETCH_COLUMN);

    // Update each product with a unique random value
    foreach ($productIds as $productId) {
        $query->bindValue(":product_id", $productId);
        $query->execute();
    }
}

function deleteImage($filename)
{
    $filename = basename($filename);
    $filename = sanitize_input($filename);
    error_log("isfile: " . is_file(realpath("uploads/" . $filename)));
    $fileCheckOk = check_image_integrity(realpath("uploads/" . $filename));
    if ($fileCheckOk) {
        unlink(realpath("uploads/" . $filename));
    }
}

function getUploadedFilesOptions($directoryPath)
{
    $selectMenuOptions = "";
    $files = scandir($directoryPath);
    print_r($files);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && is_file($directoryPath . DIRECTORY_SEPARATOR . $file)) {
            $selectMenuOptions .= '<option value="' . sanitize_input($file) . '">' . sanitize_input($file) . '</option>';
        }
    }

    return $selectMenuOptions;
}

//---------- Vulnerable Code-----------------

function searchUser($searchParam)
{
    $searchParam = str_replace(";", "", $searchParam);
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `username` LIKE '%$searchParam%'";
    return $conn->query($sql);
}

function vulnerableDisplayImage($imagePath)
{
    //Only for Challenge
    if (str_ends_with($imagePath, "txt") || str_ends_with($imagePath, "jpeg") || str_ends_with($imagePath, "png")) {
        $imageData = file_get_contents($imagePath);
        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);
        echo "<img src='" . $base64Image . "' alt='" . sanitize_input($imagePath) . "' width=40%>";
    } else {
        print("Don't break the challenge!");
    }
}

function addProduct($productName, $productDesc, $productQuant, $productImage)
{
    if (!file_exists($productImage)) {
        return;
    }

    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` = :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $productName);
    $query->execute();

    $product = $query->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        $sql = "INSERT INTO `products`(`name`, `description`, `quantity`, `image_path`) VALUES (:pname,:pdesc,:pquant,:pimage)";
        $query = $conn->prepare($sql);
        $query->bindValue("pname", sanitize_input($productName));
        $query->bindValue("pdesc", sanitize_input($productDesc));
        $query->bindValue("pquant", sanitize_input($productQuant));
        $query->bindValue("pimage", sanitize_input($productImage));
        $query->execute();
    }
}
