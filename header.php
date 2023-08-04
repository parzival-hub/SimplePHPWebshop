<head>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="w3.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/ico" href="favicon.ico" />
    <title>This is NUTS</title>
</head>

<div class="top-info-bar">
    See the <a href="challenges.php">Challenges</a>
</div>

<div class="w3-bar w3-theme w3-large" style="z-index:3;margin:10px;background-color: #f9f9f9;">
    <a class='w3-bar-item w3-left w3-button w3-hide-medium w3-hover-white w3-padding-16' href='index.php'>This is
        Nuts</a>

    <?php

if (isset($_SESSION["role"])) {
    echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='logout.php'>Logout</a>";
} else {
    echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='login.php'>Login</a>";
}

if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='admin_users.php'>Admin</a>";
}

if (isset($_SESSION["role"])) {
    echo "<a class='w3-bar-item w3-right w3-button w3-hover-white' href='cart.php'><img width='35px' src='images/shopping-cart.png' ></a>";
    echo "<a class='w3-bar-item w3-right w3-button  w3-hover-white' href='user_profile.php'><img width='35px' src='images/user.png' ></a>";
}
?>
    <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit"
        form="searchform">Search</button>
    <form class="w3-bar-item w3-right" method="GET" id="searchform" action="index.php">
        <div class="w3-row">
            <input class="w3-input" type="search" id="suche" name="s" placeholder="Search for products...">
        </div>
    </form>

</div>