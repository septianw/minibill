<?php

$Q=buildSet($_REQUEST['tpl'],'id','promocast.template_data');
mysql_query($Q);

$redirect_to="index.php?page=templateAdmin";

?>
