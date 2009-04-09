<?php

$Q=buildSet($_REQUEST['sdata'],'ship_id','mod_shipping_data');
mysql_query($Q);

$redirect_to="index.php?page=ledger";


?>
