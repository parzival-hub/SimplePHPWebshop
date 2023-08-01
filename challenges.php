<!DOCTYPE html>
<html>

<?php

include 'functions.php';
include "header.php";
error_reporting(E_ERROR | E_PARSE);

?>

<body>
    <h1 style="text-align:center">Challenges:</h1>
    <div style="text-align:center">
        <?php
$results = get_challenges();
if (!empty($results)) {
    foreach ($results as $row) {
        $id = $row["id"];
        $challengeName = $row["name"];
        $points = $row["points"];
        $timeMinutes = $row["time_minutes"];
        $description = $row["description"];
        $isSolved = $row["solved"] == "1" ? true : false;
        ?>
        <div class="challenge">
            <div style="display:flex">
                <div style='margin:15px;text-align:left'>
                    <h2><?php echo sanitize_input($challengeName) ?></h2>
                    <p>Points: <?php echo sanitize_input($points) ?></p>
                    <p>Time (minutes): <?php echo sanitize_input($timeMinutes) ?></p>
                    <p>Status: <span
                            class="<?php echo ($isSolved ? 'solved' : 'unsolved') ?>"><?php echo ($isSolved ? 'solved' : 'unsolved') ?></span>
                    </p>
                </div>
                <div style='margin:60px'>
                    <?php echo sanitize_input($description) ?>
                </div>
            </div>
            <form method='POST' action='<?php echo sanitize_input($_SERVER["PHP_SELF"]) ?>'>
                <input name='solution' placeholder='C-XXXXX-XXXXX-XXXXX'>
                <input name='id' style="display: none" value='<?php sanitize_input($id)?>'>
                <button class="w3-button w3-black">Submit</button>
            </form>
        </div><?php
}
} else {
    echo "No challenges found.";
}

$conn = null;
?>
    </div>
</body>

</html>