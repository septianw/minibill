<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
//...... Connect to database
if (@mysql_pconnect($config['db_host'],$config['db_user'],$config['db_pass']))
{
	mysql_select_db($config['db_name']);
}
else 
{
	//...... This makes sure we are installed correctly.
	if (!$_SESSION['installing'])
	{
		$_SESSION['installing'] = 1;
		session_write_close();
		header("Location: index.php?page=install");
		exit();
	}
}

/*************************************************/
/* Load the configuration Vars from the database */
/*************************************************/
if (!$_SESSION['installing'])
{
	$Q="SELECT id,variable,value FROM config";
	$res = mysql_query($Q);

	while($conf = mysql_fetch_row($res))
	{
		list($id_val,$confvar,$confvalue) = $conf;
		$config[$id_val][$confvar] = $confvalue;
	}
}

?>
