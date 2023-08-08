<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
session_regenerate_id(true);
session_unset();
session_destroy();
echo '<h2>Du hast dich erfolgreich ausgeloggt</h2>';
print('<script type="text/javascript">
function Redirect()
{
    window.location="index.php";
}
document.write("You will be redirected to home.");
setTimeout("Redirect()", 1000);
</script>');
exit();
