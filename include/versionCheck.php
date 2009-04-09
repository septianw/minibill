<?php
/**************************************************/
/* All Code Copyright 2004 Matthew Frederico      */
/* Shared with glee from me to thee Under the 'G' */
/**************************************************/

if (!$_SESSION['version_check'])
{
	//...... Version Check
	$C = curl_init("http://www.ultrize.com/minibill/index.php?action=version");
	curl_setopt($C, CURLOPT_TIMEOUT,5);
	curl_setopt($C, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($C, CURLOPT_HEADER, 0);
	$version_info = curl_exec($C);
	curl_close($C);

	list($version['major'],$version['minor'],$version['build'],$version['release']) = explode("\n",$version_info);

	if ($version['major'] > $config['version']['major'] ||
		$version['minor'] > $config['version']['minor'] ||
		$version['build'] > $config['version']['build'] ||
		$version['release'] != $config['version']['release']
		)
	{
		$new_version = "$version[major].$version[minor]:$version[build] $version[release]";
	}
	else unset($new_version);

	$C = curl_init("http://www.ultrize.com/minibill_latest_snapshot.php");
	curl_setopt($C, CURLOPT_TIMEOUT,5);
	curl_setopt($C, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($C, CURLOPT_HEADER, 0);
	$version_info = curl_exec($C);
	curl_close($C);

	list($latest_snapshot,$snapdate) = explode("\n",$version_info);
	$_SESSION['version_check'] = 1;
}


?>
