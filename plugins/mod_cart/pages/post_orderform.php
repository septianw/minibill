<?php
//...... This page has to happen AFTER the original orderform.php is processed
//...... That way, we can munge any session data correctly.
if (!$_REQUEST['verified'])
{
	foreach($_SESSION['prods'] as $prod)
	{
		$prodList .= $prod['id']."-".$prod['quantity'].',';
		$total_items += $prod['quantity'];
	}
	$_SESSION['total_items'] = $total_items;

	//....... Hijack pages
	header("Location: index.php?page=updateOrder");
	exit();
}

?>
