<?php


foreach($_SESSION['prods'] as $prod)
{
	if ($prod['weight'] > 0) 
	{
		$totalItemsToShip += $prod['quantity'];
		$totalItemsWeight += ($prod['weight'] * $prod['quantity']);
	}
}

if  ($totalItemsToShip > 0)
{
	$_SESSION['shipping_total'] = $config['mod_shipping']['flat_rate'];
	$_SESSION['grand_total'] = getGrandTotal($_SESSION['prods']) + $_SESSION['shipping_total'];

	$X->_tpl_vars['grand_total'] = $_SESSION['grand_total'];
	/* Shipping billing hack for lean4lifetime.com */
	/* This should probably live in a template .. but for now .. */
	$plugin[$thisPlugin]['appendOrderForm'][$thisPage] = <<< __EOT__
	<tr>
		<td colspan="20" style="height:1px;background:#ABCDEF;"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><b>Shipping Cost</b></td>
		<td></td>
		<td align="right">{$_SESSION['shipping_total']}</td>
	</tr>
__EOT__;

}

?>
