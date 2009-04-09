<?php
session_unregister('oc');
session_unregister('order_id');

function getCatProdCount($info)
{
	$Q="SELECT COUNT(*) as count
		FROM products 
		WHERE category_id='{$info['id']}'";
	list($info['count']) = mysql_fetch_row(mysql_query($Q));
	return($info);
}

function getProdSample($info)
{
	$Q="SELECT title FROM products 
		WHERE category_id='{$info['id']}' LIMIT 5";
	$info['prods'] = getResults($Q);
	return($info);
}

/*
function getImages($info)
{
	global $config,$pluginName,$thisPage;
	$imgTypes = array('png','jpg','gif');

	foreach($imgTypes as $suffix)	
	{

	}
	return($info);
}
*/

function getImages($info)
{
    global $config,$pluginName,$thisPage;
    $imgTypes = array('png','jpg','gif','jpeg');

    foreach($imgTypes as $suffix)
    {
        $img = $config['mod_cart']['image_url'].'thumbnail_'.$info['id'].'.'.$suffix;
        $imgFile = $config['mod_cart']['image_dir'].'thumbnail_'.$info['id'].'.'.$suffix;
        if (file_exists($imgFile))
        {
            $info['thumbnail'] = $img;
            break;
        }
    }
    return($info);
}


function getCat($info)
{
	$Q="SELECT title FROM category WHERE id='{$info['category_id']}'";
	list($cat) = mysql_fetch_row(mysql_query($Q));
	$info['category']	= $cat;

	return($info);
}

function fixSlashes($info)
{
        $info['item_desc'] = stripslashes($info['item_desc']);
        return($info);
}

$Q="SELECT id,title FROM category";
$X->assign('categories',getResults($Q,array('getCatProdCount','getProdSample')));

//...... Make sure we aren't showing products.
if (!defined('SHOWPRODUCTS'))
{
	$X->assign('title',$config['company']['name']);
	$hijackTemplate = 1;
}

$prodArray = explode(',',$config['mod_cart']['featured_products']);

foreach($prodArray as $p)
{
	$prodList .= "'$p',";
}

$prodList = substr($prodList,0,strlen($prodList) -1);


$Q="SELECT * FROM products WHERE id IN($prodList)";

$X->assign('products',getResults($Q,array("getImages","getCat","fixSlashes")));
if (!is_on($config['mod_cart']['keep_navigation'])) $X->assign('nomenu',1);

?>
