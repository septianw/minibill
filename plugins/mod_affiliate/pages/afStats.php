<?php

global $menuitem;
global $pageData;

$then	= date("Y-m-d 00:00:00",time() - (86400));
$now	= date("Y-m-d 23:59:59",time());

$Q="SELECT COUNT(*),SUM(grand_total)
    FROM
        mod_affiliate_data
    WHERE
        afid='$_SESSION[id]'
	AND
		date 
	BETWEEN
		'$then'
	AND
		'$now'
    LIMIT 1";

list($orders,$grand_total) = mysql_fetch_row(mysql_query($Q));

$X->assign('orders',number_format($orders));
$X->assign('grand_total','$'.number_format($grand_total,2));
$X->assign('then',strtotime($then));
$X->assign('now',time());

$hijackTemplate = 1;

?>
