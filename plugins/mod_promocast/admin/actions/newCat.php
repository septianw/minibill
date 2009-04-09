<?php

$Q=buildSet($_REQUEST['cat'],'id','promocast.template_cats');
print($Q);
mysql_query($Q);
$redirect_to = "index.php?page=templateAdmin";

?>
