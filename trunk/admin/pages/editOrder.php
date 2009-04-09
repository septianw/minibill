<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title','EDIT ORDER');

$status_types = array('paid'=>'Paid','due'=>'Due','suspend'=>'Suspend','cancel'=>'Cancel');
$X->assign('status_types',$status_types);

$Q="SELECT * FROM orders WHERE id='{$_GET['id']}' LIMIT 1";
$res = mysql_query($Q);
$order = mysql_fetch_assoc($res);

$Q="SELECT * FROM products";
$res = mysql_query($Q);
$p = 0;

//...... Get a list of all products
while($product = mysql_fetch_assoc($res))
{
	//...... If we are looking at the same product as in the order
	$prodlist[$product['id']] = $product['title'];
	if ($product['id'] == $order['product_id']) $product['selected'] = 'selected';
	$products[$p]  = $product;
	$p++;
}

$X->assign('prodlist',$prodlist);
$X->assign('products',$products);
$X->assign('order',$order);

?>
