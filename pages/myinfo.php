<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$Q="SELECT * FROM users WHERE id='{$_SESSION['id']}' LIMIT 1";
$res = mysql_query($Q);
$user = mysql_fetch_assoc($res);

$X->assign('title','My Information');

if ($user[id])
{
	$user['cardnum'] = data_decrypt($user['cardnum'],$config['secret_key']);
	$user['safeCard'] = substr($user['cardnum'],-4,4);

	//...... Trash the full card num
	$user['cardnum'] = '';

	$X->assign('user',$user);
}

?>
