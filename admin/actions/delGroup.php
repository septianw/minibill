<?php

$msg = base64_encode("#00A000|#FFFFFF|Order Group Deleted.");

$Q="DELETE FROM orders WHERE uniq_id='$_REQUEST[uniq_id]'";
mysql_query($Q);

$redirect_to="index.php?page=ledger&msg=$msg";

?>
