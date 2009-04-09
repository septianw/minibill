<?php

$Q="DELETE FROM promocast.mailer_campaign WHERE mc_id='{$_REQUEST['mc_id']}' LIMIT 1";
mysql_query($Q);

$Q="DELETE FROM promocast.mailer_campaigndata WHERE mcd_id='{$_REQUEST['mc_id']}' LIMIT 1";
mysql_query($Q);

$redirect_to="index.php?page=outgoingMail";

?>
