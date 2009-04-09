<?php

$Q=buildSet($_REQUEST['af'],'id','mod_affiliate');
mysql_query($Q);

$redirect_to = "index.php?page=affiliates";

?>
