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
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>This is NUTS</title>
</head>

<body>  
<header class="top-header">
    <h1> This is NUTS </h1>   
</header>
<div class="w3-row">
        <div class="w3-third" style="margin:4px 0 6px 0">
        <a href="index.php">
            <img src="images/nuts_logo.png" alt="ThisIsNutsLogo" width="70" height="60">
        </a>
    
    </div>
    
    <div class="w3-margin-top w3-wide w3-hide-medium w3-hide-small w3-right">
        <?php
            if ($loggedIn)
                echo "<a<h3>Willkommen, " . $_SESSION["username"] . "</h3></a>";
            else
             echo "<a<h3>Du bist nicht eingeloggt</h3></a>";
        ?>

    </div>
    </div>

    </div>
    <div class="w3-bar w3-theme w3-large" style="z-index:3;">
    <!--
    <a class="w3-bar-item w3-button w3-left w3-hide-large w3-hover-white w3-large w3-theme w3-padding-16" href="javascript:void(0)" onclick="w3_open()">☰</a>
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-small w3-hover-white w3-padding-16" href="javascript:void(0)" onclick="w3_show_nav('menuTut')">Produkt1</a>
    <a class="w3-bar-item w3-button w3-hide-medium w3-hover-white w3-padding-16" href="javascript:void(0)" onclick="w3_show_nav('menuRef')">Produkt2</a>
    -->
    <?php
                if ($loggedIn)
                    echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='logout.php'>LOGOUT</a>";
                    
                else
                    echo "<a class='w3-bar-item w3-right w3-button w3-hide-medium w3-hover-white w3-padding-16' href='login.php'>LOGIN</a>";
                ?>
    <button class="w3-btn w3-bar-item w3-right w3-hide-medium w3-hover-white w3-padding-16" type="submit" form="searchform">Suchen</button>
    <form class ="w3-bar-item w3-right" method="GET" id="searchform" action="<?php
        echo htmlspecialchars($_SERVER["PHP_SELF"]);
        ?>">
        <div class="w3-row">
        <input class="w3-input" type="search" id="suche" name="s" placeholder="Suche nach Produkten...">
        

        </div>

    </form>
    
    </div>

    <!--
   <div class="w3-content ">
    <img class="slideshow" src="images/slideshow_1.jpg" style="max-width: 200px;height: 100px;">
    <img class="slideshow" src="images/slideshow_2.jpg" style="max-width: 200px;height: 100px;">
    <img class="slideshow" src="images/slideshow_3.jpg" style="max-width: 200px;height: 100px;">
    <img class="slideshow" src="images/slideshow_4.jpg"style="max-width: 200px; height: 100px;">
    </div>
-->
<div class="w3-container">
  <div class="w3-card w3-hover-shadow" style="width:20%">

    <div class="w3-container w3-center">
      <h3>Haselnuss</h3>
      <img src="images/background_haselnuts.jpg" alt="Avatar" style="width:80%">
      <div class="w3-section">
        <button class="w3-button w3-green">Kaufen</button>
        <button class="w3-button w3-red">Mehr Infos</button>
      </div>
    </div>

  </div>
</div>



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
<!--
<script>
var myIndex = 0;
carousel();

function carousel() {
  var i;
  var x = document.getElementsByClassName("slideshow");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  myIndex++;
  if (myIndex > x.length) {myIndex = 1}    
  x[myIndex-1].style.display = "block";  
  setTimeout(carousel, 2000); // Change image every 2 seconds
}
</script>
-->

</body>