<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$msg = base64_encode("#00A000|#FFFFFF|User account deleted.");

$Q="DELETE FROM users WHERE id='$_GET[id]'";
mysql_query($Q);
header("Location: $_SERVER[HTTP_REFERER]&msg=$msg");

?>
