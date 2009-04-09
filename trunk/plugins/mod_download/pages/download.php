<?php


$Q="SELECT * FROM mod_download AS md,mod_download_data AS mdd 
	WHERE md.file_id=mdd.file_id 
	AND mdd.hidden !='1'
	AND user_id='$_SESSION[id]'";

$res = mysql_query($Q);
while ($file = mysql_fetch_assoc($res))
{
	if (file_exists($config['mod_download']['download_dir'].$file['name']))
	{
		$file['size'] = $file['size'] = format_filesize(filesize($config['mod_download']['download_dir'].$file['name']));
		$files[] = $file;
	}
}

$X->assign('files',$files);
$X->assign('title','DOWNLOADS');

//....... Hijack the templates
$plugin[$thisPlugin]['output'] = $X->fetch("$thisPage.html");
$pageData .= $X->fetch("$thisPage.html");

?>
