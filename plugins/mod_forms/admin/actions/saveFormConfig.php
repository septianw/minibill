<?php

if (!isset($_REQUEST['form']['id']))
{
	$_REQUEST['form']['date'] = date("Y-m-d H:i:s");
}

$Q = buildSet($_REQUEST['form'],'id','mod_forms');
mysql_query($Q);

$redirect_to = "index.php?page=forms&msg=".base64_encode("#00A000|#FFFFFF|Form Configuration Saved.");
?>
