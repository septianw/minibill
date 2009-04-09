<?php

$Q="UPDATE mod_forms_answer 
	SET user_id='$_SESSION[id]', 
	uniq_id='$_SESSION[order_id]' 
	WHERE session_id='$_REQUEST[PHPSESSID]'";
mysql_query($Q);

?>
