<?php

$msg = base64_encode("#00A000|#FFFFFF|Variable $_GET[variable] Deleted.");

if (!$DEMO_MODE)
{
	$Q="DELETE FROM config WHERE id='$_GET[id]' AND variable='$_GET[variable]' LIMIT 1";
	mysql_query($Q);
	header("Location: index.php?page=editConfig&id=$_GET[id]&msg=$msg");
}

?>
