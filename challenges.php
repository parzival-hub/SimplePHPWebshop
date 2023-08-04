<!DOCTYPE html>
<html>

<?php
session_start();
include 'functions.php';
include "header.php";
error_reporting(E_ERROR | E_PARSE);

#print(hash_hmac("sha512", "C-Thats-AWier-dName", "FJk!br!5"));
#print(hash_hmac("sha512", "C-iWasH-idden-SoBad", "FJk!br!5"));

?>

<body>
    <h1 style="text-align:center">Challenges:</h1>
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
        <h2><?php echo sanitize_input($challengeName) ?></h2>
        <p>Points: <?php echo sanitize_input($points) ?> | Time (minutes):
            <?php echo sanitize_input($timeMinutes) ?> |Status: <span
                class="<?php echo ($isSolved ? 'solved' : 'unsolved') ?>"><?php echo ($isSolved ? 'solved' : 'unsolved') ?></span>
        </p>

        <p style="text-align:left;margin:30px"><?php echo sanitize_input($description) ?></p>
        <?php if (!$row["solved"]) {?>
        <form method='POST' action='api.php'>
            <input name='solution' placeholder='C-XXXXX-XXXXX-XXXXX'>
            <input name='id' style="display: none" value='<?php echo sanitize_input($id) ?>'>
            <button class="w3-button w3-black">Submit</button>
        </form>
        <?php }?>
    </div><?php

    }
} else {
    echo "No challenges found.";
}

$conn = null;
?>

    <form method='POST' action='api.php' style="margin:10px">
        <input name='reset' style="display: none">
        <button class="w3-button w3-red">Reset all challenges</button>
    </form>
</body>

</html>