<?php

//..... Delete all templates in this category
$Q="SELECT id FROM promocast.template_data WHERE cat_id='{$_REQUEST['id']}'";
$res = mysql_query($Q);
while(list($tplId) = mysql_fetch_row($res))
{
	$Q="DELETE FROM promocast.template_data WHERE id='{$tplId}' LIMIT 1";
	mysql_query($Q);
}

$Q="DELETE FROM promocast.template_cats WHERE id='{$_REQUEST['id']}' LIMIT 1";
mysql_query($Q);

$redirect_to="index.php?page=templateAdmin";
?>
