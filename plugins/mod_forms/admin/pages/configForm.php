<?php

$Q="SELECT id,title FROM products";
$res = mysql_query($Q);
while($product = mysql_fetch_assoc($res))
{
    if (!strlen($product[title])) $product[title] = 'Item #'.$product[id];
    $products[$product[id]] = $product['title'];
}
$X->assign('products',$products);

$Q="SELECT * FROM mod_forms WHERE id='$_REQUEST[id]' LIMIT 1";
$res = mysql_query($Q);
$formInfo = mysql_fetch_assoc($res);

$X->assign('form',$formInfo);

//....... Hijact the templates
$hijackTemplate = 1;
$thisPage = $config['plugin_dir'].$pluginName.'/templates/admin/'.$thisPage;

?>
