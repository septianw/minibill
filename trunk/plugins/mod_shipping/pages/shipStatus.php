<?php

function setTotals($data)
{
	$data['grand_total'] = number_format($data['amount'] * $data['quantity'],2);
	$data['quantity']	 = number_format($data['quantity']);
	return($data);	
}

function getOrder($data)
{
	if ($data['uniq_id'] == $_REQUEST['uniq_id'])
	{
		$Q="SELECT * FROM orders,products 
			WHERE uniq_id='$_REQUEST[uniq_id]' 
			AND products.id=orders.product_id";
		$data['order'] = getResults($Q,'setTotals');
	}
	return($data);
}

$Q="SELECT * FROM mod_shipping_data 
	WHERE user_id='{$_SESSION['id']}'";
$data = getResults($Q,array('getOrder'));

if ($_REQUEST['uniq_id'])
{
	$Q="SELECT 
			* 
		FROM 
			mod_shipping
		WHERE 
			uniq_id='{$_REQUEST['uniq_id']}' 
		LIMIT 1";
	$shipTo = getResults($Q);

	if (!$shipTo) 
	{
		$Q="SELECT 
				firstname,lastname,address as address1,city,state,zipcode,country 
			FROM 
				users
			WHERE 
				id='{$_SESSION['id']}' 
			LIMIT 1";
		$shipTo = getResults($Q);
	}

	$X->assign('shipTo',$shipTo[0]);
}

$X->assign('title','Shipping Information');
$X->assign('shippingData',$data);

$hijackTemplate = 1;

?>
