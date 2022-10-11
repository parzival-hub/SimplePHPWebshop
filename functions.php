<?php

// error_reporting(0);
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace("%", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(")", "", $data);
    return $data;
}

function getUser()
{
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `username` = :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $_SESSION["username"]);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function login($name, $role)
{
    session_regenerate_id(true);
    $_SESSION["username"] = $name;
    $_SESSION["role"] = $role;
    echo "<script>window.location.assign('index.php');</script>";
    exit();
}

function getConnection()
{
    return new PDO('mysql:host=127.0.0.1;dbname=xiks5egieksn6c6a;charset=utf8mb4', 'rm3AER5PkBnnEiTg', 'aS7HFRb94!@t3LTR', array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false
    ));
}

function addProduct($productName, $productDesc, $productQuant, $productImage)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` = :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $productName);
    $query->execute();

    $results = [];
    // Parse returned data, and displays them
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($results, $row);
    }

    if (empty($results)) {
        $sql = "INSERT INTO `products`(`name`, `description`, `quantity`, `image_path`) VALUES (:name,:desc,:quant,:image)";
        $query = $conn->prepare($sql);
        $query->bindValue("name", $productName);
        $query->bindValue("desc", $productDesc);
        $query->bindValue("quant", $productQuant);
        $query->bindValue("image", $productImage);
        $query->execute();
    } else {
        echo '<script type="text/javascript">
        window.onload = function () { alert("Produkt existiert bereits!"); }
        </script>';
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

    $row = $query->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function search($searchParam)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` LIKE :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", "%" . $searchParam . "%");
    $query->execute();

    $results = [];
    // Parse returned data, and displays them
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($results, $row);
    }
    return $results;
}

function loginAllowed($username, $clear_password)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `username`=? AND `password`=?";
    $query = $conn->prepare($sql);
    $query->bindValue(1, $username);
    $query->bindValue(2, hash_hmac("sha512", $clear_password, "FJk!br!5"));
    $query->execute();

    if ($query->rowCount() == 1) {
        return $query->fetch(PDO::FETCH_ASSOC)["role"];
    } else
        return "None";
}

function addUser($username, $email, $password, $role)
{
    $conn = getConnection();

    $creationsql = "CREATE TABLE :username (name VARCHAR(255) NOT NULL, quantity INT(3), description VARCHAR(500) NOT NULL, image_path VARCHAR(100))";
    $creation_query = $conn->prepare($creationsql);
    $creation_query->bindValue("username", $username);
    $creation_query->execute();

    $insert_sql = "INSERT INTO `users`(`username`, `password`, `email`,`role`) VALUES (':username',':password',':email',':role')";
    $insert_query = $conn->prepare($insert_sql);
    $insert_query->bindValue("username", $username);
    $insert_query->bindValue("password", $password);
    $insert_query->bindValue("email", $email);
    $insert_query->bindValue("role", $role);
    $insert_query->execute();
}

function searchUser($searchParam)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `users` WHERE `username` LIKE :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", "%" . $searchParam . "%");
    $query->execute();

    $results = [];
    // Parse returned data, and displays them
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($results, $row);
    }
    return $results;
}

function addToCart($productName, $quantity, $username)
{
    $conn = getConnection();
    $sql = "SELECT * FROM `products` WHERE `name` = :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", $productName);
    $query->execute();

    $results = [];
    // Parse returned data, and displays them
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($results, $row);
    }
    if (count($results) == 1) {

        $sql = "SELECT * FROM " . $_SESSION['username'] . " WHERE `name` = :searchq";
        $query = $conn->prepare($sql);
        $query->bindValue("searchq", $productName);
        $query->execute();
        $checking = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($checking, $row);
        }
        // Falls Produkt nicht vorhanden, füge hinzu
        if (empty($checking)) {
            $max_quant = $results[0]["quantity"];
            if ($quantity > $max_quant)
                $quantity = $max_quant;
            $sql = "INSERT INTO " . $username . " (`name`, `description`, `quantity`, `image_path`) VALUES (:name,:desc,:quant,:image)";
            $query2 = $conn->prepare($sql);
            $query2->bindValue("name", $productName);
            $query2->bindValue("desc", $results[0]["description"]);
            $query2->bindValue("quant", $quantity);
            $query2->bindValue("image", $results[0]["image_path"]);
            $query2->execute();
        } // Falls Produkt schon vorhanden, erhöhe um quant
        else {
            $max_quant = $results[0]["quantity"] - $checking[0]["quantity"];
            if ($quantity > $max_quant)
                $quantity = $max_quant;
            $sql = "UPDATE " . $username . " SET  quantity = quantity + :quant WHERE name = :searchq";
            $query2 = $conn->prepare($sql);
            $query2->bindValue("searchq", $productName);
            $query2->bindValue("quant", $quantity);
            $query2->execute();
        }
    }
}

function searchCart($searchParam, $username)
{
    $conn = getConnection();
    $sql = "SELECT * FROM " . $username . " WHERE `name` LIKE :searchq";
    $query = $conn->prepare($sql);
    $query->bindValue("searchq", "%" . $searchParam . "%");
    $query->execute();

    $results = [];
    // Parse returned data, and displays them
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($results, $row);
    }
    return $results;
}

function deleteProductCart($productName, $username)
{
    $conn = getConnection();
    $sql = "DELETE FROM " . $username . " WHERE `name`=:delParam";
    $query = $conn->prepare($sql);
    $query->bindValue("delParam", $productName);
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

function changePassword($oldpass, $newpass)
{
    $conn = getConnection();
    $sql = "UPDATE users SET password = '" . hash_hmac("sha512", $newpass, "FJk!br!5") . "' WHERE username = ':userName' AND password=':pass'";
    $query = $conn->prepare($sql);
    $query->bindValue("userName", $_SESSION["username"]);
    $query->bindValue("pass", hash_hmac("sha512", $oldpass, "FJk!br!5"));
    $query->execute();
}

function changeUser($username, $changeParam, $changePlace)
{
    $conn = getConnection();
    if ($changePlace == "username") {
        $sql = "ALTER TABLE ':changePlace' RENAME TO ':changeParam'";
        $query = $conn->prepare($sql);
        $query->bindValue("changePlace", $username);
        $query->bindValue("changeParam", $changeParam);
        $query->execute();
    } else if ($changePlace == "email") {
        $sql = "UPDATE users SET :changePlace = '" . $changeParam . "' WHERE username = ':changeParam'";
        $query = $conn->prepare($sql);
        $query->bindValue("changePlace", $changePlace);
        $query->bindValue("changeParam", $changeParam);
        $query->execute();
    }
}
