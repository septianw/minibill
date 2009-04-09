<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title','ORDER DETAIL');

$Q="SELECT * FROM products";
$res = mysql_query($Q);
while($prod = mysql_fetch_assoc($res))
{
	$prodList[$prod[id]] = $prod;
}

if ($_REQUEST[user_id])
{
	$Q="SELECT firstname,lastname FROM users WHERE id='$_REQUEST[user_id]' LIMIT 1";
	list($customer_name) = mysql_fetch_row(mysql_query($Q));

	$Q="SELECT * FROM orders WHERE user_id='$_REQUEST[user_id]' ORDER BY date_purchased DESC";
}

if ($_REQUEST[uniq_id])
	$Q="SELECT * FROM orders WHERE uniq_id='$_REQUEST[uniq_id]' ORDER BY date_purchased DESC";



$res = mysql_query($Q);
while($info = mysql_fetch_assoc($res))
{
	$orderTotal += $info['amount'];
	$itemTotal  += $info['quantity'];

	$info['amount']		= number_format($info['amount'],2);
	$info['product'] = $prodList[$info['product_id']];

	$orders[] = $info;
	$numOrders++;
}


$X->assign('orderTotal',number_format($orderTotal,2));
$X->assign('itemTotal',number_format($itemTotal));
$X->assign('numOrders',number_format($numOrders));
$X->assign('customer_name',$customer_name);
$X->assign('orders',$orders);

?>
