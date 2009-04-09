<?php

// Send email to user
$Q="SELECT 
		q_u_id 
	FROM 
		promocast.mailer_content 
	WHERE 
		q_id='{$_REQUEST['q_id']}' 
	LIMIT 1";
list($user_id) = mysql_fetch_row(mysql_query($Q));


$Q="SELECT 
		value
	FROM 
		promocast.config 
	WHERE 
		id='global' 
	AND 	
		variable='system_email'";

list($sys_email) = mysql_fetch_row(mysql_query($Q));

$Q="SELECT 
		email,firstname,lastname 
	FROM 
		promocast.users 
	WHERE 
		id='$user_id' 
	LIMIT 1";

list($email,$fn,$ln) = mysql_fetch_row(mysql_query($Q));
print mysql_error();

mail("$fn $ln <$email>","PromoCast Message Declined",$_REQUEST['reason'],"Content-Type: text/plain\nFrom: Promocast Message Center <{$sys_email}>","-t");

// Delete form queue
$Q="DELETE FROM
		promocast.mailer_campaign 
	WHERE
		mc_status=100
	AND
		mc_mailer_id='{$_REQUEST['q_id']}'";
mysql_query($Q);

// redirect
$redirect_to = "index.php?page=mailApproval";

?>
