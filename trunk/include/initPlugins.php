<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/

//...... We're in the admin DIR .. so relative is ../
#if ($config['admin']['enabled'] == 'true') 
#	$config['plugin_dir'] = $config['admin']['plugin_dir'];

//...... Loads plugins config files
//...... We do this so plugins can hijack config variables if necessary

//...... Okay, apparently there was some halliabaloo about the following 
//...... code on http://securitytracker.com/alerts/2006/Aug/1016769.html
//...... Frankly, this is safe as register_globals (99% of the time) is OFF
//...... Also, I'd be pissed if you ran MiniBill - or ANY PHP e-commerce software
//...... for that matter with safe_mode off ... 

//...... But, without further ado ... Here is the fix

/* FIX */
if (!defined('LOADED')) 
{ 
	print("<h1>Possible hacking attempt</h1>");
	print("<h3>This has been recorded and logged, have a nice day!</h3>");
	error_log("Breakin Attempt detected in: ".__FILE__." from {$_SERVER['REMOTE_ADDR']}");
	exit(); 
}
/* FIX */

if ($pd = opendir($config['plugin_dir']))
{
	while (false !== ($pluginName = readdir($pd))) 
	{
        if (!preg_match("/\.|\.\./",$pluginName) && is_on($config['plugins'][$pluginName]))
		{
			if (file_exists($config['plugin_dir']."$pluginName/config.php"))
			{
				include_once($config['plugin_dir']."$pluginName/config.php");
			}
			if (file_exists($config['plugin_dir']."$pluginName/ajax_functions.php"))
			{
				include_once($config['plugin_dir']."$pluginName/ajax_functions.php");
			}

			/*********************************************************/
			/* Generate preg for non authenticated actions and pages */
			/*********************************************************/
			if(isset($plugin[$pluginName]['non_auth_actions']))
			{
				foreach($plugin[$pluginName]['non_auth_actions'] as $value)
				{
					if (strlen($value)) $NON_AUTH_ACTIONS .= "$value|";
				}
			}

			if(isset($plugin[$pluginName]['non_auth_pages']))
			{
				foreach($plugin[$pluginName]['non_auth_pages'] as $value)
				{
					if (strlen($value)) $NON_AUTH_PAGES .= "$value|";
				}
			}
		}
	}
}
?>
