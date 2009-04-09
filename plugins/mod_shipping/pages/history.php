<?php

for ($x = 0;$x < count($X->_tpl_vars['orders']);$x++)
{
	$Q="SELECT shipping_cost FROM mod_shipping_data WHERE uniq_id='{$X->_tpl_vars['orders'][$x]['uniq_id']}' LIMIT 1";
	list($shipping_cost) = mysql_fetch_row(mysql_query($Q));
	$X->_tpl_vars['orders'][$x]['grand_total'] += $shipping_cost;
	$total_shipping_cost += $shipping_cost;
}

$X->_tpl_vars['total_paid'] =  number_format(floatVal(str_replace(',','',$X->_tpl_vars['total_paid']))+$shipping_cost,2);

?>
