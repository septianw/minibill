<?php

//...... Set up definitions
$name           = 'mod_custom';
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

    //...... Set up non authenticated actions (preg_match) (if applicable)
    $plugin[$name]['non_auth_actions'][] = '';

    //...... Set up non authenticated pages (preg_match) (if applicable)
    $plugin[$name]['non_auth_pages'][]	= '';


	/*************************************************/
	/* Run code to check if your module is installed */
	/*************************************************/

	/*********************************************************/
	/* Look for table name with same name as definition name */
	/*********************************************************/
	$res = mysql_query("SHOW TABLE STATUS LIKE '$name'");
	list($installed) =  mysql_fetch_row($res);


	//...... If it is not installed:
	if (!$installed)
	{
		/**************************************************/
		/* Run your automatic installation functions here */
		/**************************************************/

		/**************************************************/
		/* Searches fot the .sql file of the defined name */
		/**************************************************/
		$sqlFile = ($config['plugin_dir']."$name/sql/$name.sql");

		//...... Display a quick installation message
		if (loadSqlData($sqlFile))
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
	}
}

?>
