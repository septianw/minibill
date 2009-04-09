<?php

foreach($_REQUEST['quantity'] as $item=>$quantity)
{
	$Q="SELECT title,stock FROM products WHERE id='$item' LIMIT 1";
	list($title,$inStock) = mysql_fetch_row(mysql_query($Q));

	if ($inStock > 0 && $quantity > $inStock) 
	{
		$msg[] = base64_encode("#A00000|#FFFFFF|Sorry, but we don't have more than <b>$inStock</b> of <b>$title</b> in our inventory.  <br/>Since you were asking for a larger amount, we will give you the maximum we currently have in our inventory.");
		$quantity = $inStock;
	}

	//...... This removes it from the cart.
	if ($quantity == 0)
	{
		foreach($_SESSION['prods'] as $count=>$prod)
		{
			if ($prod['id'] == $item)
			{
				unset($_SESSION['prods'][$count]);
			}
		}
	}
	else updateProd($item,'quantity',$quantity);

	$_SESSION['total_items'] = $quantity;
}

if (isset($msg)) foreach($msg as $m) $fMsg .= "&msg[]=$m";

if ($_REQUEST['order_id']) $v .= "&order_id=".$_REQUEST['order_id'];

session_write_close();

$redirect_to = "index.php?page=orderform&order$fMsg";

?>
