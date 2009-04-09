<?php

if (intval($_REQUEST['id']) > 1)
{
	$Q[]="DELETE FROM promocast.users WHERE id='{$_REQUEST['id']}' LIMIT 1";
	$Q[]="DELETE FROM promocast.user_credits WHERE user_id='{$_REQUEST['id']}' LIMIT 1";
	$Q[]="DELETE FROM promocast.user_conf WHERE c_u_id='{$_REQUEST['id']}'";
	$Q[]="DELETE FROM promocast.mailer_campaign WHERE mc_user='{$_REQUEST['id']}'";
	$Q[]="DELETE FROM promocast.mailer_content WHERE q_u_id='{$_REQUEST['id']}'";

	foreach($Q as $QRY)
	{
		mysql_query($QRY);
		if (mysql_error()) 
		{
			print $QRY;
			print mysql_error();
			exit();
		}
	}
}

?>
