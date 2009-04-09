<?php

$Q="SELECT id,title FROM products";
$res = mysql_query($Q);
while($product = mysql_fetch_assoc($res))
{		
	if (!strlen($product[title])) $product[title] = 'Item #'.$product[id];
	$products[$product[id]] = $product['title'];
}
$X->assign('products',$products);

$Q="SELECT * FROM mod_download";
$res = mysql_query($Q);

if ($res)
{
	while($file = mysql_fetch_assoc($res))
	{
		if (file_exists($config['mod_download']['download_dir'].$file['name']))
		{
			//...... Gets REAL file size.
			$file['size'] = format_filesize(filesize($config['mod_download']['download_dir'].$file['name']));
			$files[$f] = $file;
			$files[$f]['tied_item'] = $products[$file['product_id']];
			$f++;
		}
	}
	$X->assign('files',$files);
}

//....... Hijack the template for this output only.
$hijackTemplate = 1;

?>
