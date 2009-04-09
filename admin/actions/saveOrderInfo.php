<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


//...... Create update set
if (!isset($_REQUEST['delete']))
{
	$Q = buildSet($_REQUEST['order'],'id','orders');
	mysql_query($Q);
	$msg = base64_encode("#00A000|#FFFFFF|Order Updated");
	$redirect_to = ("index.php?page=editOrder&id={$_REQUEST['order']['id']}&msg={$msg}");
}
//...... Delete this order (delete flag sent = $_REQUEST[delete])
else
{
	$ord = new OrderClass($user_id,0,$config);
	$order_count = $ord->delOrder($uniq_id,$_REQUEST['order']['id']);

	$msg = base64_encode("#00A000|#FFFFFF|Order {$_REQUEST['order']['id']} Deleted");
	//..... Redirect to order group listing 
	//..... if we have more than 1 order in the list
	if ($order_count > 1)
	{
		$redirect_to = ("index.php?page=showGroup&uniq_id=$uniq_id&msg=$msg");
	}
	else
	{
		$redirect_to = ("index.php?page=ledger&msg=$msg");
	}
}

?>
