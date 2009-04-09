<?php

foreach($_REQUEST[order] as $key=>$value)
{
	$Q="UPDATE mod_forms_question SET q_order='$value' WHERE id='$key'";
	mysql_query($Q);
}

$redirect_to = "index.php?page=editForm&id=".$_REQUEST['form_id']."&msg=".base64_encode("#00A000|#FFFFFF|Question order updated");

?>
