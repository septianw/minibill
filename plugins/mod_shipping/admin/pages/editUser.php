<?php

$Q="SELECT * FROM mod_shipping WHERE user_id='$_REQUEST[id]' LIMIT 1";
$res = mysql_query($Q);
if ($res)
	$shipping = mysql_fetch_assoc($res);

$X->assign('shipping',$shipping);


$Q="SELECT * FROM mod_shipping_data WHERE user_id='$_REQUEST[id]' ORDER BY shipped,packaged DESC";
$shippingData = getResults($Q);

$X->assign('shippingData',$shippingData);

?>
