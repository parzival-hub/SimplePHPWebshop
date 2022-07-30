<?php
include 'functions.php';
session_start();

if (array_key_exists('valid', $_SESSION) && $_SESSION["valid"])
    $loggedIn = true;
else
    $loggedIn = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<header>
    <div id="top-header">
        <nav>
            <ul >
                <li class="active"><a href="#"><button>Shop</button></a></li>
                <li><a href="#"><button>Contact</button></a></li>
                <?php
                if ($loggedIn)
                    echo "<li><a href=logout.php><button>Logout</button></a></li>";
                else
                    echo "<li> <a href=login.php><button>Login</button></a></li>";
                ?>
            </ul>
        </nav>
    </div>
</header>

<body>
<main>

<?php
if ($loggedIn)
    echo "<h1>Willkommen, " . $_SESSION["username"] . "</h1>";
else
    echo "<h1>Du bist nicht eingeloggt</h1>";
?>

 <form method="GET" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">
  <input type="search" id="suche" name="s" placeholder="Suche nach produkten...">
  <button>Search</button>
</form>

<div class="grid-container">
<?php
// init suchparameter
if (isset($_GET["s"]) && ! empty($_GET["s"]))
    $search_param = $_GET["s"];
else
    $search_param = "";

$results = search($search_param);
foreach ($results as $item) {
    $imageHTML = '<img src=' . $item["image_path"] . '></img>';
    echo '<div class="grid-item"><p>' . $item['name'] . '</p>' . $imageHTML . $item['description'] . '</div>';
}
?>
</div>
</main>
</body>
</html>