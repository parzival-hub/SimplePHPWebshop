<?php
error_reporting(E_ERROR | E_PARSE);

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace("%", "", $data);
    $data = str_replace("{", "", $data);
    $data = str_replace("}", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("%", "", $data);
    return $data;
}

function loginAllowed($username, $clear_password)
{
    $password = hash_hmac("sha512", $clear_password, "FJk!br!5");
    $conn = getConnection();

    $sql = "SELECT * FROM `users` WHERE `username`=? AND `password`=?";
    $query = $conn->prepare($sql);
    $query->bindValue(1, $username);
    $query->bindValue(2, $password);
    $query->execute();

    if ($query->rowCount() == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return "None";
    }

}

function getConnection()
{
    return new PDO('mysql:host=127.0.0.1;dbname=xiks5egieksn6c6a;charset=utf8mb4', 'rm3AER5PkBnnEiTg', 'aS7HFRb94!@t3LTR', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
    ));
}

function addProduct($productName, $productDesc, $productQuant, $productImage)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` = :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $productName);
    $query->execute();

    $product = $query->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        $sql = "INSERT INTO `products`(`name`, `description`, `quantity`, `image_path`) VALUES (:pname,:pdesc,:pquant,:pimage)";
        $query = $conn->prepare($sql);
        $query->bindValue("pname", $productName);
        $query->bindValue("pdesc", $productDesc);
        $query->bindValue("pquant", $productQuant);
        $query->bindValue("pimage", $productImage);
        $query->execute();
    } else {
        echo '<script type="text/javascript">alert("Produkt existiert bereits!");</script>';
    }
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

function searchUser($searchParam)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `username` LIKE :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", "%" . $searchParam . "%");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
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

// Falls User noch nicht vorhanden ist, gib true zurück
function checkUserDatabank($userName)
{
    $conn = getConnection();
    $sql = "SELECT * FROM users WHERE `username`=:userParam";
    $query = $conn->prepare($sql);
    $query->bindValue("userParam", $userName);
    $query->execute();
    $checking = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($checking, $row);
    }
    if (empty($checking)) {
        return true;
    } else {
        return false;
    }
}

function create_user($username, $password, $email, $role)
{
    if (checkUserDatabank($username)) {
        $conn = getConnection();
        $query = $conn->prepare("INSERT INTO `users`(`username`, `password`, `email`,`role`) VALUES (:username,:pass,:email,:userRole)");
        $query->bindValue("username", $username);
        $query->bindValue("pass", hash_hmac("sha512", $password, "FJk!br!5"));
        $query->bindValue("email", $email);
        $query->bindValue("userRole", $role);
        $query->execute();
        echo '<script type="text/javascript"> alert("Benutzer erstellt!");</script>';
    }
}

function deleteUser($username)
{
    $conn = getConnection();
    $sql = "DELETE FROM users WHERE `username`=:delParam";
    $query = $conn->prepare($sql);
    $query->bindValue("delParam", $username);
    $query->execute();
    $sql = "DROP TABLE " . $username;
    $query = $conn->prepare($sql);
    $query->execute();
}

function changeUser($username, $changeParam, $changePlace)
{
    $conn = getConnection();
    if ($changePlace != "password") {
        $sql = "UPDATE users SET " . $changePlace . " = '" . $changeParam . "' WHERE username = '" . $username . "'";
        $query = $conn->prepare($sql);
        $query->execute();
        if ($changePlace == "username") {
            $sql = "ALTER TABLE " . $username . " RENAME TO " . $changeParam;
            $query = $conn->prepare($sql);
            $query->execute();
        }
    } else if ($changePlace == "password") {
        $sql = "UPDATE users SET " . $changePlace . " = '" . hash_hmac("sha512", $changeParam, "FJk!br!5") . "' WHERE username = '" . $username . "'";
        $query = $conn->prepare($sql);
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