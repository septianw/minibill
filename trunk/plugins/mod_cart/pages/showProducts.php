<?php
define('SHOWPRODUCTS',1);
include("pages/cart.php");

function findImages($info)
{
	global $config,$pluginName,$thisPage;
	$imgTypes = array('png','jpg','gif','jpeg');

	foreach($imgTypes as $suffix)	
	{
		$img = $config['mod_cart']['image_url'].'largeimage_'.$info['id'].'.'.$suffix;
		$imgFile = $config['mod_cart']['image_dir'].'largeimage_'.$info['id'].'.'.$suffix;
		if (file_exists($imgFile))
		{
			$info['largeimg'] = $img;
			break;
		}
	}
	return($info);
}

$X->assign('pluginName',$pluginName);

$Q="SELECT title,id FROM category WHERE id='{$_GET['category_id']}'";
list($cat,$cat_id) = mysql_fetch_row(mysql_query($Q));
$X->assign('category',$cat);
$X->assign('category_id',$cat_id);

$Q="SELECT * FROM products WHERE category_id='{$_GET['category_id']}'";
$X->assign('products',getResults($Q,array("findImages")));

if (!is_on($config['mod_cart']['keep_navigation'])) $X->assign('nomenu',1);

//....... Hijack the templates
$plugin[$thisPlugin]['output'] = $X->fetch("$thisPage.html");
$pageData .= $plugin[$thisPlugin]['output'];

?>
