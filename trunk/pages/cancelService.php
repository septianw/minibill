<?php

$Q="SELECT * FROM users WHERE id='$_SESSION[id]' LIMIT 1";
$res = mysql_query($Q);
$user = mysql_fetch_assoc($res);

$Q="SELECT * FROM orders,products WHERE order_id='$_REQUEST[id]' AND product_id=id LIMIT 1";
$res = mysql_query($Q);
$order = mysql_fetch_assoc($res);

$service[user] = $user;
$service[order] = $order;

$X->assign('title','Service cancellation');
$X->assign("service",$service);

?>
