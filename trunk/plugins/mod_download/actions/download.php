<?php
//** This script performs the actual file download

$Q="SELECT name 
	FROM mod_download AS md, mod_download_data AS mdd 
	WHERE md.file_id='$_REQUEST[file_id]' 
	AND mdd.file_id='$_REQUEST[file_id]' 
	AND mdd.activated > '0000-00-00' 
	AND mdd.hidden='0' LIMIT 1";

$res = mysql_query($Q);

list($filename) = mysql_fetch_row($res);

if ($filename)
{
	if (file_exists($config['mod_download']['download_dir'].$filename))
	{
		$file = ($config['mod_download']['download_dir'].$filename);

		header("Content-Type: application/octet-stream");
		header("Content-Length: ".filesize($file));

		if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT']) || preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT']))
		{
			header('Content-Disposition: filename="'.$filename.'"');
		}
		else
		{
			header('Content-Disposition: attachment; filename="'.$filename.'"');
		}

		readfile($file);
		$Q="UPDATE mod_download 
			SET last_downloaded=NOW(),
			times_downloaded=times_downloaded+1 
			WHERE file_id=$_REQUEST[file_id]";
		mysql_query($Q);

		$Q="UPDATE mod_download_data 
			SET is_downloaded=is_downloaded+1,
			last_downloaded=NOW()
			WHERE user_id='$_SESSION[id]'
			AND file_id='$_REQUEST[file_id]' LIMIT 1";
		mysql_query($Q);
	}
	else
	{
		header("HTTP/1.0 404 Not Found");
		print "<h1>File not found. </h1>";
		print $config['mod_download']['download_dir'].$filename;
		print "<hr>Please contact the administrator: {$config['mbadmin']['email']}";
	}
}
else
{
	header("HTTP/1.0 403 Forbidden");
	print "<h1>Access Forbidden</h1>";
}

?>
