<?php

$Q="DELETE FROM promocast.template_data WHERE id='{$_REQUEST['id']}' LIMIT 1";
mysql_query($Q);
$redirect_to="index.php?page=templateAdmin";

?>
