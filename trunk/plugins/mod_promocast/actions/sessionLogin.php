<?php

// rmd5 un/pass and redirect to normal login

list($id,$pass,$date) = explode("|",data_decrypt($_REQUEST['id'],'elijah'));

//http://localhost/minibill//index.php?page=cart&&jumpTo=page%3Dcart&forward=&PHPSESSID=k466piknubfg6aktgmc6cvs767

//...... As long as my date stamp correlates within 60 seconds, we're good.
if ($date <= time() && $date >= time() - 60)
{
	$Q="SELECT * FROM users 
		WHERE id='$id' 
		AND password='".addslashes($pass)."' 
		LIMIT 1";

	$res	= mysql_query($Q);
	$user	= mysql_fetch_assoc($res);

	if (mysql_num_rows($res))
	{
		foreach($user as $key=>$value)
		{
			$_SESSION[$key] = $value;
		}
	}
	else
	{
		$msg = "?msg=".base64_encode("#A00000|#FFFFFF|Invalid Billing User");
	}

	
	unset($_REQUEST['id']);
	$jumpTo = $_REQUEST['jumpTo'];
	unset($_REQUEST['jumpTo']);

	foreach($_REQUEST as $key=>$value)
	{
		$qs .= "&$key=".urlencode($value);
	}

	if (strlen($jumpTo)) 
		$redirect_to = ("index.php?{$jumpTo}&$msg$qs"); 
	else  
		$redirect_to = ("index.php?$msg");
}
else
{
	$msg = "&msg=".base64_encode("#A00000|#FFFFFF|Unable to fulfill request - session timed out ".date("Y-m-d H:i:s",$date)." / ".date("Y-m-d H:i:s")." {$id} {$password}");
	$redirect_to = ($_SERVER['HTTP_REFERER']."?$msg");
}

?>
