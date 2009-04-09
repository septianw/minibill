<?php

$Q="SELECT * FROM mod_download_data AS mdd, mod_download AS md WHERE user_id='$_GET[id]' AND mdd.file_id=md.file_id";
$res = mysql_query($Q);

while($file = mysql_fetch_assoc($res))
{
	$file['size'] = format_filesize($file['size']);
	$userfiles[] = $file;
}

//...... In theory, this gets all the files NOT in users's account.
$Q="SELECT * FROM mod_download md,products WHERE md.product_id=products.id AND md.hidden=0;";

$res = mysql_query($Q);

while($file = mysql_fetch_assoc($res))
{
	$file['size'] = format_filesize($file['size']);
	$files[] = $file;
}

$X->assign('files',$files);
$X->assign('userfiles',$userfiles);
$X->assign('files',$files);

?>
