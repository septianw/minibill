<?php

$Q=buildSet($_REQUEST['interface'],'id','mod_interface');
mysql_query($Q);

$redirect_to="index.php?page=interface";

?>
