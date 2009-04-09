<?php


session_destroy();
$msg = base64_encode("#008000|#FFFFFF|<b>You have been logged out of the system.");
header("Location: index.php?page=afLogin&msg=$msg");

?>
