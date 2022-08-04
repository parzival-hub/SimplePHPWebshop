<?php

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login($name, $role)
{
    $_SESSION["username"] = $name;
    $_SESSION["role"] = $role;
    header('Location: index.php', true, 301);
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
    $sql = "SELECT * FROM `products` WHERE `name` LIKE :searchq";
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
            $sql = "INSERT INTO " . $username . " (`name`, `description`, `quantity`, `image_path`) VALUES (:name,:desc,:quant,:image)";
            $query2 = $conn->prepare($sql);
            $query2->bindValue("name", $productName);
            $query2->bindValue("desc", $results[0]["description"]);
            $query2->bindValue("quant", $quantity);
            $query2->bindValue("image", $results[0]["image_path"]);
            $query2->execute();
        } // Falls Produkt schon vorhanden, erhöhe um 1
        else {
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

//Falls User noch nicht vorhanden ist, gib true zurück
function checkUserDatabank($userName){
    $conn = getConnection();
    $sql = "SELECT * FROM users WHERE `username`=:userParam";
    $query = $conn->prepare($sql);
    $query->bindValue("userParam", $userName);
    $query->execute();
    $checking = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        array_push($checking, $row);
    }
    if(empty($checking)){
        return true;
    }
    else{
        return false;
    }

}
