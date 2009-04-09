<?php

$Q="UPDATE
		mod_promocast
	SET
		credits='{$_REQUEST['credit']['credits']}',
		promoCredits='{$_REQUEST['credit']['promoCredits']}'
	WHERE 
		prod_id='{$_REQUEST['credit']['id']}'";

$res = mysql_query($Q);

if (!mysql_affected_rows())
{
	$Q="INSERT INTO
			mod_promocast
		SET
			credits='{$_REQUEST['credit']['credits']}',
			promoCredits='{$_REQUEST['credit']['promoCredits']}',
			prod_id='{$_REQUEST['credit']['id']}'";
	mysql_query($Q);
}

$msg = base64_encode("#008000|#FFFFFF|PromoCast Credit value updated");
$redirect_to="index.php?page=editProduct&id={$_REQUEST['credit']['id']}&msg=$msg";

?>
