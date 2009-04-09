<?php


foreach($_REQUEST['file'] as $file_id=>$values)
{
	$Q="SELECT name FROM mod_download WHERE file_id='$file_id' LIMIT 1";
	list($oldName) = mysql_fetch_row(mysql_query($Q));

	rename($config['mod_download']['download_dir'].$oldName,$config['mod_download']['download_dir'].$values['name']);

	$Q="UPDATE mod_download SET
		name='".addslashes($values['name'])."',
		product_id='$values[product_id]',
		description='".addslashes($values['description'])."'
		WHERE file_id='$file_id' LIMIT 1";
	mysql_query($Q);
}

$msg = "#00A000|#FFFFFF|File information saved.";
$redirect_to='index.php?page=files&msg='.base64_encode($msg);

?>
