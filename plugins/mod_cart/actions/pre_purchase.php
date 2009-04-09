<?php

//..... We don't need to verify the order again now do we?
$_REQUEST['verified'] = 1;

//...... Set the product list back to what we started with
if (isset($_SESSION['prods']))
{
	foreach($_SESSION['prods'] as $prod)
		$prodList .= $prod['id']."-".$prod['quantity'].',';
	$_REQUEST['order'] = $prodList;
}
else $redirect_to = $config['secure']['cart'];


?>
