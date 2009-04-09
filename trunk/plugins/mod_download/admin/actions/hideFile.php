<?php

$Q="SELECT hidden FROM mod_download WHERE file_id='$_REQUEST[file_id]' LIMIT 1";
list($hidden) = mysql_fetch_row(mysql_query($Q));

$hidden = ($hidden > 0) ? 0 : 1;

$Q="UPDATE mod_download SET
	hidden='$hidden' WHERE file_id='$_REQUEST[file_id]'";

mysql_query($Q);

$redirect_to = 'index.php?page=files';

?>
