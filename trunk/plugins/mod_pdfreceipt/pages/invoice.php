<?php

function grandTotal($data)
{
	$data['grand_total'] = number_format($data['amount'] * $data['quantity'],2);
	$data['quantity']	 = number_format($data['quantity']);
	return($data);	
}

function getOrderTotals($data)
{
	$Q="SELECT amount,quantity,date_purchased FROM orders
		WHERE uniq_id='$data[uniq_id]' ";
	list($data['totals']) = getResults($Q,'grandTotal');
	return($data);
}

$Q="SELECT DISTINCT(uniq_id) 
	FROM orders 
	WHERE user_id='$_SESSION[id]' ORDER BY date_purchased DESC";
$data = getResults($Q,array('getOrderTotals'));

$hijackTemplate = 1;
$X->assign('title','Printable Invoices');
$X->assign('invoices',$data);

?>
