<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
//...... Connect to database
mysql_pconnect($config['db_host'],$config['db_user'],$config['db_pass']);
mysql_select_db($config['db_name']);

//...... This makes sure we are installed correctly.
if (mysql_error())
{
	if (is_writable($config['session_save_path']))
	{
		$_SESSION['installing'] = 1;
		session_write_close();
		header("Location: index.php?page=install");
	}
	else 
	{
		print "<h1>Your sessions cannot be saved to {$config['session_save_path']}</h1>";
		print "<hr><h3>Please make sure that directory is writable by the webserver</h3>";
	}
	exit();
}
/*************************************************/
/* Load the configuration Vars from the database */
/*************************************************/
$Q="SELECT id,variable,value FROM config";
$res = mysql_query($Q);

while($conf = mysql_fetch_row($res))
{
	list($id_val,$confvar,$confvalue) = $conf;
	$config[$id_val][$confvar] = $confvalue;
}

?>
