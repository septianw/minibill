<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

global $sysMsg;
//...... Get this user information
//$Q="SELECT * FROM users WHERE email='$_SESSION[email]' AND password='$_SESSION[password]' LIMIT 1";
$Q="SELECT * FROM users WHERE id='$_SESSION[id]'";
$res = mysql_query($Q);
$user = mysql_fetch_assoc($res);

$X->assign('title',"Order History");

if ($user[id])
{
	//..... Show all the orders from this user[id]
	$Q="SELECT * FROM orders,products WHERE user_id='$user[id]' AND orders.product_id=products.id ORDER BY payment_due DESC";
	$res = mysql_query($Q);

	$grand_total =0;
	$now = time();
	while($order = mysql_fetch_assoc($res))
	{
		$grand_total += (floatval($order['amount']) * floatval($order['quantity']));
		$total_paid  += ($order['status'] == 'paid') ? (floatval($order['amount']) * floatval($order['quantity'])) : 0;
		$total_due   += ($order['status'] == 'due' || $order['status'] == 'due') ? (floatval($order['amount']) * floatval($order[quantity])) : 0;

		$order['date_purchased']	= date("Y-m-d",strtotime($order['date_purchased']));
		$order['grand_total']		= number_format((floatval($order['amount']) * floatval($order['quantity'])),2);
		$order['amount']		= number_format((floatval($order['amount'])),2);
		$orders[] = $order;
	}

	//...... Only show last 4 digits of credit card
	$user['safecard'] = substr(data_decrypt($user['cardnum'],$config['secret_key']),strlen(data_decrypt($user['cardnum'],$config['secret_key'])) -4,4);

	$X->assign('total_paid',number_format($total_paid,2));
	$X->assign('total_due',number_format($total_due,2));
	$X->assign('grand_total',number_format($grand_total,2));
	$X->assign('orders',$orders);
	$X->assign('user',$user);
}
else
{
	session_destroy();
	$sysMsg->addMessage('Please use the correct username and password.');
}

?>
