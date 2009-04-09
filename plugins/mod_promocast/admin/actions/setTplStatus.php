<?php

foreach($_REQUEST['tpl'] as $id=>$values)
{
	$Q="UPDATE 
			promocast.template_data 
		SET 
			active='{$values['active']}'
		WHERE 
			id='{$id}'";
	mysql_query($Q);
	print mysql_error();
}

$redirect_to="index.php?page=templateAdmin&id={$_REQUEST['id']}";

?>
