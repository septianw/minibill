<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$msg = base64_encode("#00A000|#FFFFFF|Template {$_REQUEST['id']} Deleted");

//...... Probably check to see if it exists in a category, and if it does, don't delete it.
if (!$DEMO_MODE)
{
	$Q="DELETE FROM templates WHERE id='".addslashes($_REQUEST['id'])."'";
	mysql_query($Q);
}
header("Location: index.php?page=templates&msg=$msg");

?>
