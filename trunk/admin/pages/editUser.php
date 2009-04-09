<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/
$X->assign('title','EDIT USER');

$Q="SELECT * FROM orders,products WHERE user_id='$_GET[id]' AND orders.product_id=products.id LIMIT 10";
$res = mysql_query($Q);
print mysql_error();

$grand_total = 0;
while($info = mysql_fetch_assoc($res))
{
	$grand_total += ($info['amount'] * $info['quantity']);
	$prod[] = $info;
}


$X->assign('prods',$prod);
$X->assign('grand_total',number_format($grand_total,2));

$Q="SELECT * FROM users WHERE id='$_GET[id]' LIMIT 1";
$res = mysql_query($Q);
print mysql_error();

$info = mysql_fetch_assoc($res);

foreach($info as $key=>$value)
{
	if ($key == 'cardnum') 
	{
		$user['safeCard'] = substr(data_decrypt($value,$config['secret_key']),-4,4);
		unset($user[$key]);
	}
	$user[$key] = addslashes($value);
}
$X->assign('user',$user);

?>
