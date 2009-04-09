<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

//...... WTF is this here for?
//session_start();

if(($_POST['mbadmin'] == $config['mbadmin']['login']) && 
	($_POST['mbpass'] == $config['mbadmin']['pass']))
{
	foreach($_POST as $key=>$value)
	{
		$_SESSION[$key] = $value;
	}
}
else
{
	$msg = "?msg=".base64_encode("#A00000|#FFFFFF|Invlid Login Attempted<br/><b>Your information has been logged.</b>");
}

header("Location: index.php".$msg);

?>
