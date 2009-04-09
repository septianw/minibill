<?php


$Q="SELECT company,remote_key FROM mod_interface";
$res = mysql_query($Q);
while ($info = mysql_fetch_assoc($res))
{
	$interfaces[$info[remote_key]] = $info['company'];
}

$Q="SELECT remote_prod_id,remote_key FROM mod_interface_prodLookup WHERE id='$_REQUEST[id]' LIMIT 1";
$interface = mysql_fetch_assoc(mysql_query($Q));

$plugin[$thisPlugin][$thisFile]['pos']		= 'leftSide';

$X->assign('interface',$interface);
$X->assign('interfaces',$interfaces);

?>
