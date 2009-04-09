<?php

$Q="SELECT id,title FROM products";
$res = mysql_query($Q);
while($product = mysql_fetch_assoc($res))
{
    if (!strlen($product[title])) $product[title] = 'Item #'.$product[id];
    $products[$product[id]] = $product['title'];
}

$X->assign('products',$products);

//....... Hijact the templates
$hijackTemplate = 1;
$thisPage = $config['plugin_dir'].$pluginName.'/templates/admin/'.$thisPage;

?>
