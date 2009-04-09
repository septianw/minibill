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

// can't use buildset here because this is an addon, use REPLACE INTO xx SET
if ($shipping)
{
	foreach($shipping as $key=>$value)
		$Q .= "$key='".addslashes($value)."',";

	//...... Trin trailing comma
	$Q=substr($Q,0,strlen($Q)-1);

	//...... Final query
	$Q = "REPLACE INTO mod_shipping SET $Q";
	mysql_query($Q);

	if (mysql_error())
	{
		print mysql_error();
		exit();
	}
}

?>
