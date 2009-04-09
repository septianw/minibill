<?php
//...... Weird, but .. 
//...... This module has to happen AFTER the original orderform.php is processed
//...... That way, we can munge any session data correctly.

//...... Don't need the menu
if (!is_on($config['mod_cart']['keep_navigation'])) $X->assign('nomenu',1);

if (!$_REQUEST['verified'])
{
	$X->assign('prods',$_SESSION['prods']);
	$X->assign('grand_total',number_format($_SESSION['grand_total'],2));
	$X->assign('title','Update cart contents');

	//....... Hijack the templates
	$hijackTemplate = 1;
}

?>
