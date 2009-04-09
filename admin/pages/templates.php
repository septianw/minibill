<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title','TEMPLATES');

$Q="SELECT * FROM templates order by name";
$res = mysql_query($Q);
while($info = mysql_fetch_assoc($res))
{
	$csize = strlen($info['content']);
	$info['size'] = $csize;
	$info['stamp'] = date("Y-m-d H:i:s",$info['stamp']);
	$template[] = $info;	
}

$submenu['addTemplate'] = "NEW TEMPLATE";

$X->assign('submenu',$submenu);
$X->assign('template',$template);

?>
