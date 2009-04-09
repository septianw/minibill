<?php

$name           = 'mod_shipping';
$enabled        = 'true';
$version        = '1.0';
$author         = 'Matthew Frederico';
$license        = 'GPL / Public';

//create the ultimate plugin array
$plugin[$name] = array( 'enabled'   =>$enabled,
                        'version'   =>$version,
                        'license'   =>$license,
                        'author'    =>$author);

//...... Auto-Installation details for module
if (is_on($config['plugins'][$name]))
{
	$installed	= '';
	$sqlFile	= '';
	$res		= '';
	$msg 		= '';

	$res = mysql_query("SHOW TABLE STATUS LIKE '$name'");
	list($installed) =  mysql_fetch_row($res);

	if (!$installed)
	{
		$sqlFile = ($config['plugin_dir']."$name/sql/$name.sql");
		$loaded = loadSqlData($sqlFile);
		if ($loaded > 0)
		{
			$msg = <<<__EOT__
<table width="100%">
	<tr><th colspan="2">$name</th></tr>
	<tr><td><b>Author</b></td><td>$author</td></tr>
	<tr><td><b>Version</b></td><td>$version</td></tr>
	<tr><td><b>License</b></td><td>$license</td></tr>
	<tr><td colspan="2"><h3>$name plugin Installed.</h3>
</table>
__EOT__;
			
			$sysMsg->addMessage("$msg");
		}

		elseif ($loaded == 0)
			$sysMsg->addMessage("Cannot install $name: can't find $sqlFile");

		elseif ($loaded == -1)
			$sysMsg->addMessage("Cannot install $name:<br /><b>".mysql_error()."<//b>");
	}
}

?>
