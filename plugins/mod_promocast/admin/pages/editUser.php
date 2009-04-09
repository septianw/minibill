<?php

$Q="SELECT * FROM promocast.users,promocast.user_credits WHERE id='{$_REQUEST['id']}' AND user_id=id LIMIT 1";
$user = mysql_fetch_assoc(mysql_query($Q));

$plugin[$thisPlugin][$thisFile]['pos']  = "aboveLeftSide";
$X->assign('promoUser',$user);

?>
