<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title',"CUSTOMER INVOICING");

$Q="SELECT id,firstname,lastname,email FROM users";
$res = mysql_query($Q);
while($client = mysql_fetch_assoc($res))
{
	$clients[] = $client;
}
$X->assign('clients',$clients);

//...... Create the invoice
$Q="SELECT * FROM products";
$res = mysql_query($Q);
unset($prods);
while($prod = mysql_fetch_assoc($res))
{
	$prods[] = $prod;
}

$X->assign('prods',$prods);

?>
