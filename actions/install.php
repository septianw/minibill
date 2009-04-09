<?php
global $PROJECT;

$sqlFile = 'sql/'.$PROJECT.'.sql';

$_SESSION['install'] = $_REQUEST;

//...... Saves the config file with our new config values
include("include/readConf.class.php");
$c = new ReadConf('config.php');
$c->assignVars($_REQUEST['conf']);
$c->parseConf();
if (!$c->writeConf()) 
{
	header("Location: index.php?page=install&msg=".base64_encode("#A00000|#FFFFFF|Please make sure your permissions are correct for config.php (chmod 666 config.php)"));
	exit();
}

session_write_close();

//..... Re-include our config with changes
include("config.php");

@mysql_pconnect($config['db_host'],$config['db_user'],$config['db_pass']);
if (mysql_error())
{
	header("Location: index.php?page=install&msg=".base64_encode("#A00000|#FFFFFF|Please check your mysql database settings:<br />".mysql_error()));
	exit();
}

//...... Do we have an error?
if (strlen($error))
{
	if ($_REQUEST['REF'])
	{
		header("Location: ".base64_decode($_REQUEST[REF])."&msg=".base64_encode(nl2br($error)));
	}
	else header("Location: $_SERVER[HTTP_REFERER]&msg=".base64_encode(nl2br($error)));

}
else
{
	//...... Creates our initial database
	mysql_query("CREATE DATABASE $config[db_name]");
	mysql_select_db($config['db_name']);

	if (mysql_error() && mysql_errno() != 1007)
	{
		header("Location: index.php?page=install&msg=".base64_encode("#A00000|#FFFFFF|Please check your mysql database settings:<br />".mysql_error()));
		exit();
	}

	$sqlLoaded = loadSqlData($sqlFile);

	if ($sqlLoaded == 0)
	{
		header("Location: index.php?page=install&msg=".base64_encode("#A00000|#FFFFFF|$sqlFile not found!"));
		exit();
	}
	if ($sqlLoaded == -1)
	{
		header("Location: index.php?page=install&msg=".base64_encode("#A00000|#FFFFFF|Mysql error:" .mysql_error()));
		exit();
	}

	//...... The No sql updates checkbox was checked.
	if (!$_REQUEST['nosql'])
	{
		//..... Go through each query and send them to mysql
		foreach($qry as $Q)
		{
			mysql_query($Q);		
			if (mysql_error())
			{
				header("Location: index.php?page=install&msg=".base64_encode("#A00000|#FFFFFF|Please check your mysql database settings:<br />".mysql_error()));
				exit();
			}
		}
	}
	session_start();
	session_unset('installing');
	session_unset('install');
    unset($_SESSION['installing']);
    unset($_SESSION['install']);
    session_write_close();

	header("Location: admin/index.php?msg=".base64_encode("#00A000|#FFFFFF|Installation Complete!<Br />You may now log in and configure your settings:<br /><blockquote>admin / admin</blockquote>"));
}

?>
