<?php
$hijackTemplate=1;

$Q="SELECT 
		* 
	FROM 
		promocast.mailer_content 
	WHERE 
		q_id='{$_REQUEST['id']}' 
	LIMIT 1";
$message = mysql_fetch_assoc(mysql_query($Q));

$X->assign('message',$message);


?>
