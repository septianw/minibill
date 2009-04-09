<?php
function getUserInfo($info)
{
    $Q="SELECT firstname,lastname,email FROM users WHERE id='$info[user_id]' LIMIT 1";
    list($fn,$ln,$em) = mysql_fetch_row(mysql_query($Q));

    $info['email']      = $em;
    $info['firstName']  = ucfirst($fn);
    $info['lastName']   = ucfirst($ln);

    return($info);
}

$Q="SELECT user_id FROM orders WHERE uniq_id='{$_REQUEST['uniq_id']}' LIMIT 1";
list($user_id) = mysql_fetch_row(mysql_query($Q));

$Q="SELECT * FROM mod_shipping_data WHERE uniq_id='{$_REQUEST['uniq_id']}' LIMIT 1";
$sdata = mysql_fetch_assoc(mysql_query($Q));

//..... If we don't have an entry, create one
if (!$sdata) 
{
	$Q="INSERT INTO 
			mod_shipping_data 
		SET 
			user_id='{$user_id}',
			uniq_id='{$_REQUEST['uniq_id']}',
			packaged=NOW(),
			shipped=NOW()";
		
	mysql_query($Q);
	$sdata['ship_id'] = mysql_insert_id();
}

$user = getUserInfo($sdata);

$X->assign('sdata',$sdata);
$X->assign('user',$user);

$hijackTemplate=1;

?>
