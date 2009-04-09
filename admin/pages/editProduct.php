<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


/*
id           | int(10) unsigned
title        | varchar(255)   
item_desc    | text          
id  | int(10) unsigned 
price        | float(5,2)      
stock        | int(11)        
is_recurring | enum('no','Daily','Weekly','Monthly','Yearly') 
*/
$X->assign('title','ADD PRODUCTS');

$Q="SELECT * FROM products WHERE id='$_REQUEST[id]' LIMIT 1";
$res = mysql_query($Q);
$prod = mysql_fetch_assoc($res);
$prod['title'] = htmlentities(stripslashes($prod['title']));
$prod['item_desc'] = htmlentities(stripslashes($prod['item_desc']));

//...... Get a list of the categories
$Q="SELECT id,title FROM category";
$res = mysql_query($Q);
if ($res)
{
	while($cats = mysql_fetch_assoc($res))
	{
		$cat[$cats[id]] = $cats['title'];
	}
}

//...... Pull recurring types from product is_recurring field
$recurring_types = getEnumValues('products','is_recurring');
foreach($recurring_types as $recur)
{
	$r[$recur] = $recur;
}


$X->assign("prod",$prod);
$X->assign("recurring",$r);
$X->assign("cats",$cat);

?>
