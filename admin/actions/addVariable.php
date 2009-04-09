<?php

$Q="REPLACE INTO config SET variable='".addslashes($_REQUEST[newvar])."',value='".addslashes($_REQUEST[newvar])."' WHERE id='$_REQUEST[id]' LMIIT 1";
print $Q;
//mysql_query($Q);

?>
