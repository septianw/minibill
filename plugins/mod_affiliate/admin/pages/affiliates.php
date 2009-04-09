<?php

$Q="SELECT
		id,
		name,
		email,
		password,
		rip,
		COUNT(*) AS orders,
		SUM(grand_total) AS commission 
	FROM 
		mod_affiliate,mod_affiliate_data
	GROUP BY 
		email 
	ORDER BY orders DESC";

$Q="SELECT 
		id,name,email,password,rip,remote_key,COUNT(*) AS orders,SUM(grand_total) AS commission 
	FROM 
		mod_affiliate 
	LEFT JOIN 
		mod_affiliate_data 
	ON (id=afid) 
	GROUP BY id";

$res = mysql_query($Q);

while($info = mysql_fetch_assoc($res))
{
	$Q="SELECT 	
			COUNT(*),
			SUM(amount) 
		FROM 
			mod_affiliate_payouts 
		WHERE 
			afid='$info[id]' 
		LIMIT 1";

	list($payments,$paid) = mysql_fetch_row(mysql_query($Q));

	$info['payments']	= $payments;
	$info['paid']		= ($paid == 0) ? "0" : number_format($paid,2);
	$info['balance' ]	= number_format($info['commission'] - $paid,2);

	$totals['commission']+= floatval($info['commission']);
	$totals['payouts']	+= floatval($info['paid']);
	$totals['balance']	+= floatval($info['balance']);
	$totals['orders']	+= intval($info['orders']);

	$affiliates[] = $info;
}

$totals['payouts'] = number_format($totals['payouts'],2);
$totals['balance'] = number_format($totals['balance'],2);
$totals['orders'] = number_format($totals['orders']);
$totals['commission'] = number_format($totals['commission'],2);

$X->assign('affiliates',$affiliates);
$X->assign('totals',$totals);

$submenu['afNew'] = 'ADD AFFILIATE';

$hijackTemplate = 1;

?>
