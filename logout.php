<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
session_unset();
session_destroy();
echo '<h2>Du hast dich erfolgreich ausgeloggt</h2>';
print('<script type="text/javascript">   
function Redirect() 
{  
    window.location="index.php"; 
} 
document.write("You will be redirected to home."); 
setTimeout("Redirect()", 2000);   
</script>');
exit();
?>