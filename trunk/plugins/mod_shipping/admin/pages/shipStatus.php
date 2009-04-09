<?php


function getUserInfo($info)
{
	$Q="SELECT firstname,lastname,email FROM users WHERE id='$info[user_id]' LIMIT 1";
	list($fn,$ln,$em) = mysql_fetch_row(mysql_query($Q));

	$info['email']		= $em;
	$info['firstName']	= ucfirst($fn);
	$info['lastName']	= ucfirst($ln);

	return($info);
}

$Q="SELECT * FROM mod_shipping_data WHERE shipped='0000-00-00 00:00:00'";
$res = mysql_query($Q);

$shipInfo = getResults($Q,'getUserInfo');

$X->assign('shipInfo',$shipInfo);
$hijackTemplate=1;

?>
