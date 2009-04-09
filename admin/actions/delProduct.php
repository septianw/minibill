<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$msg = base64_encode("#00A000|#FFFFFF|Product Deleted.");

if (!$DEMO_MODE)
{
	$Q="DELETE FROM products WHERE id='$_REQUEST[id]' LIMIT 1";
	mysql_query($Q);
}
header("Location: index.php?page=products&msg=$msg");

?>
