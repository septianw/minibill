<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/ 

$X->assign('title','USER ACCOUNTS');

if (isset($_REQUEST[search]))
{
	if ($_REQUEST['search']['type'] == 'name')
	{
		$WHERE = "firstname LIKE '%".addslashes($_REQUEST['search']['phrase'])."%' OR lastname LIKE '%".addslashes($_REQUEST['search']['phrase'])."%'";
	}
	else
	{
		$WHERE = $_REQUEST['search']['type']." LIKE '%".addslashes($_REQUEST['search']['phrase'])."%'";
	}
	$WHERE = "WHERE ".$WHERE;
}

$Q="SELECT * FROM users $WHERE";
$res = mysql_query($Q);

$i =0;
while($user = mysql_fetch_assoc($res))
{
	$Q="SELECT SUM(amount) FROM orders WHERE user_id='$user[id]'";
	list($user['totals']) = mysql_fetch_row(mysql_query($Q));
	$users[$i] = $user;	
	$i++;
}

$submenu['addUser']  = "ADD USER";
$X->assign('submenu',$submenu);

$X->assign('users',$users);
$X->assign('SELECT'.$_REQUEST['search']['type'],"SELECTED");

?>
