<?php

$Q="DELETE FROM mod_interface WHERE id='$_REQUEST[id]'";
mysql_query($Q);

$redirect_to = "index.php?page=interface";
?>
