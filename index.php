<?php
session_start();

$sql = new PDO('mysql:host=127.0.0.1;dbname=xiks5egieksn6c6a;charset=utf8mb4', 'rm3AER5PkBnnEiTg', 'aS7HFRb94!@t3LTR', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_PERSISTENT => false
));

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
</main>
</body>
</html>