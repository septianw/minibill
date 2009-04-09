<?php

$Q="SELECT * FROM mod_shipping WHERE user_id='$_SESSION[id]' LIMIT 1";
$res = mysql_query($Q);
$shipping = mysql_fetch_assoc($res);
$X->assign('shipping',$shipping);

?>
