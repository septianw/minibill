<?php
global $X;

$id = ($_SESSION['id'] > 0) ? $_SESSION['id'] : $_SESSION['user']['id'];

if ($id)
{
	if (!isset($_SESSION['shipping']))
	{
		$Q="SELECT * FROM mod_shipping WHERE user_id='$id' LIMIT 1";
		$sres = mysql_query($Q);
		$shipping = mysql_fetch_assoc($sres);

		$_SESSION['shipping'] = $shipping;
	}
	else $shipping = $_SESSION['shipping'];

	$X->assign('shipping',$shipping);
}

$plugin[$thisPlugin][$thisFile]['pos']      = 'appendFormRight';

?>
