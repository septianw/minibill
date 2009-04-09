<?php

$uploaddir = $config['mod_download']['download_dir'];
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) 
{
	$fileSize = $_FILES[file][size];
	$Q="REPLACE INTO mod_download SET 
		name='".addslashes($_FILES[file][name])."',
		size='$fileSize',
		description='".addslashes($_REQUEST[description])."',
		hidden='$_REQUEST[hidden]',
		product_id='$_REQUEST[product_id]',
		fdate=NOW()";
	mysql_query($Q);

	$redirect_to = ("index.php?page=files&msg=".base64_encode("Upload Successful!"));
}
else
{
	$redirect_to = ("index.php?page=files&msg=".base64_encode("Upload Failed -> $uploadfile"));
}
?>
