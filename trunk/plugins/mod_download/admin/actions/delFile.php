<?php
	$Q="SELECT name FROM mod_download WHERE file_id='$_REQUEST[file_id]' LIMIT 1";
	list($oldName) = mysql_fetch_row(mysql_query($Q));

	unlink($config['mod_download']['download_dir'].$oldName);

	$Q="DELETE FROM mod_download WHERE file_id='$_REQUEST[file_id]'";
	mysql_query($Q);
	header("Location: index.php?page=files");
?>
