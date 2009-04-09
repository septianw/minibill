<?php

$Q="UPDATE 
		promocast.mailer_campaign 
	SET
		mc_status=0
	WHERE
		mc_status=100
	AND
		mc_mailer_id='{$_REQUEST['q_id']}'";

mysql_query($Q);
$redirect_to = "index.php?page=mailApproval";

?>
