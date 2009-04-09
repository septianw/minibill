<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$shipping				= $_REQUEST['shipping'];
$shipping['uniq_id']	= $_SESSION['order_id'];
list($shipping['user_id'])	= explode('.',$_SESSION['order_id']);

if (count($shipping))
{
	$Q = buildSet($shipping,'','mod_shipping');
	mysql_query($Q);
}
$sdata['uniq_id'] = $_SESSION['order_id'];
$sdata['user_id'] = $_SESSION['id'];

$sdata['shipping_cost'] = $_SESSION['shipping_total'];

$Q=buildSet($sdata,'','mod_shipping_data');
mysql_query($Q);

?>
