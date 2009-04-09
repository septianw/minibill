<?php

$Q="SELECT id FROM mod_interface_prodLookup WHERE id='{$_REQUEST['interface']['id']}' LIMIT 1";
list($has_id) = mysql_fetch_row( mysql_query($Q));

if ($has_id) $Q=buildSet($_REQUEST['interface'],'id','mod_interface_prodLookup');
else $Q=buildSet($_REQUEST['interface'],'','mod_interface_prodLookup');

mysql_query($Q);

$redirect_to = "index.php?page=editProduct&id=".$_REQUEST['interface']['id'];

?>
