<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/
global $sysMsg;
session_start();
session_destroy();

$msg = base64_encode("#00A000|#FFFFFF|You have been logged out.");
$redirect_to = ("index.php?msg=$msg");

header("Location: $redirect_to");
exit();

?>
