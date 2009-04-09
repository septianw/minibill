<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$Q= '';
//...... Save user shipping information.
$shipping = $_REQUEST['shipping'];

$Q = buildSet($shipping,'user_id','mod_shipping');

$res = mysql_query($Q);
if (!mysql_affected_rows())
{
	$Q = buildSet($shipping,'','mod_shipping');	
	mysql_query($Q);
}

?>
