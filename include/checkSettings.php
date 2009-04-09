<?php

//...... You can turn this off by creating a custom config variable in config.php: settings -> stopcheck
if ($config['settings']['stopcheck'] == 0)
{
	if (ini_get('register_globals')) 
	{
		$globalsMsg = "#800000|#FFFFFF|Please turn off register_globals for best results:<br /><a href=http://www.php.net/register_globals>See php.net for details</a><br /><a href=http://securitytracker.com/alerts/2006/Aug/1016769.html>Click here to make you a believer</a>";
		if (!is_array($_REQUEST['msg']))
		{
			$_REQUEST['msg'] = array($_REQUEST['msg'], base64_encode($globalsMsg));
		}
		else $_REQUEST['msg'][] = base64_encode("$globalsMsg");
	}

	if (!ini_get('safe_mode')) 
	{
		$safeModeMsg = "#800000|#FFFFFF|For best security practice, please turn safe mode on!";
		if (!is_array($_REQUEST['msg']))
		{
			$_REQUEST['msg'] = array($_REQUEST['msg'], base64_encode($safeModeMsg));
		}
		else $_REQUEST['msg'][] = base64_encode($safeModeMsg);
	}
}

?>
