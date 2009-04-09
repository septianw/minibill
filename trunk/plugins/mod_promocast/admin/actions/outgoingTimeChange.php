<?php


$Q="UPDATE promocast.mailer_campaign SET mc_send_date='{$_REQUEST['mc_send_date']}' WHERE mc_id='{$_REQUEST['mc_id']}'";
mysql_query($Q);

$msg = base64_encode("#008000|#FFFFFF|<h2>Message time updated!</h2>");

$redirect_to="index.php?page=outgoingMail&msg=$msg";

?>
