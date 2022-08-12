<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
session_unset();
session_destroy();
echo '<h2>Du hast dich erfolgreich ausgeloggt</h2>';
print_r($_SESSION);
header('Location: index.php');
exit();
?>