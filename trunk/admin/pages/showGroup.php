<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title','ORDER GROUP');

$Q="SELECT * FROM orders WHERE uniq_id='$_GET[uniq_id]'";
$res = mysql_query($Q);
$p = 0;
$grand_total = 0;
$grand_quantity = 0;
while($order = mysql_fetch_assoc($res))
{
	$Q="SELECT title FROM products WHERE id='{$order['product_id']}' LIMIT 1";
	list($order['title']) =  mysql_fetch_row(mysql_query($Q));
	$order['grand_total'] = number_format($order['amount'] * $order['quantity'],2);
	$orders[$p] = $order;
	$p++;

	//...... Grand totals
	$grand_total 		 += $order['amount'] * $order['quantity'];
	$grand_quantity		 += $order['quantity'];
}

$X->assign('grand_total',number_format($grand_total,2));
$X->assign('grand_quantity',$grand_quantity);
$X->assign('orders',$orders);

?>
