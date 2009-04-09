<?php

function getProds($info)
{
	$Q="SELECT title,products.id,remote_prod_id FROM mod_interface_prodLookup as mil,products WHERE remote_key='$info[remote_key]' AND products.id=mil.id";
	$prods = getResults($Q);
	$info['prods'] = $prods;
	return($info);
}

$Q="SELECT * FROM mod_interface";
$interfaces = getResults($Q,'getProds');

$X->assign('randMD5',md5(time()));
$X->assign('interfaces',$interfaces);

$hijackTemplate=1;
?>
