<?php


$Q="SELECT
		*
	FROM
		mod_affiliate
	WHERE
		id='{$_REQUEST['id']}'
	LIMIT 1";

list($af) = getResults($Q);

$X->assign('af',$af);
$hijackTemplate =1;
		

?>
