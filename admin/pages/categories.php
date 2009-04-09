<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


$X->assign('title',"CATEGORIES");

$Q="SELECT * FROM category";
$res = mysql_query($Q);

while($cat = mysql_fetch_assoc($res))
{
	$categories[] = $cat;
}

$submenu['addCategory'] = "NEW CATEGORY";
$submenu['products']	= "PRODUCTS";

$X->assign('submenu',$submenu);
$X->assign('cats',$categories);

?>
