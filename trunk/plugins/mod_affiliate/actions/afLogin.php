<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

//...... Make sure we're stuck here.
$_SESSION['STANDALONE'] = $thisPlugin;

$Q="SELECT * FROM mod_affiliate 
	WHERE email='{$_REQUEST['email']}' 
	AND password='{$_REQUEST['password']}' 
	LIMIT 1";

$res	= mysql_query($Q);
$user	= mysql_fetch_assoc($res);

if (mysql_num_rows($res))
{
	/* For some reason, array_merge ($_SESSION = array_merge($_SESSION,$user)) doesn't work. */
    foreach($user as $key=>$value)
    {
        $_SESSION[$key] = $value;
    }


	$msg = "&msg=".base64_encode("#00A000|#FFFFFF|Logged In okay!");
	$redirect_to = ("index.php?page=afStats".$msg);
}
else
{
	$msg = "&msg=".base64_encode("#A00000|#FFFFFF|Invalid affiliate login");
	$redirect_to = ("index.php?page=afLogin".$msg);
}


?>


?>
