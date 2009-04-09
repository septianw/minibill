<?php

$Q="SELECT promoCredits,credits FROM mod_promocast WHERE prod_id='{$_REQUEST['id']}'";
list($promoCredits,$credits) = mysql_fetch_row(mysql_query($Q));

$plugin[$thisPlugin][$thisFile]['pos']		= 'leftSide';

$X->assign('credits',$credits);
$X->assign('promoCredits',$promoCredits);

?>
