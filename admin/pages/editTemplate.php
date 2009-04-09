<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title','EDIT TEMPLATE');


if (isset($_GET['id']))		$Q="SELECT * FROM templates WHERE id='".addslashes($_GET['id'])."' LIMIT 1";
if (isset($_GET['name']))	$Q="SELECT * FROM templates WHERE name='".addslashes($_GET['name'])."' LIMIT 1";

$res = mysql_query($Q);
$template = mysql_fetch_assoc($res);
$template['content']	= stripslashes($template['content']);
$template['name']	= stripslashes($template['name']);

$X->assign('template',$template);

?>
