<?php

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login($name)
{
    $_SESSION["username"] = $name;
    $_SESSION["valid"] = true;
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