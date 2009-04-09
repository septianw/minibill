<?php

function trimTitle($info)
{
	$info['title'] = substr($info['title'],0,8)."...";
	return($info);
}

$Q="SELECT 
		title,SUM(quantity) AS orders 
	FROM 
		products 
	LEFT JOIN 
		orders
	ON
		(product_id=id)
	GROUP BY 
		title 
	ORDER BY 
		orders 
	DESC
	LIMIT 
		5";

$prods = getResults($Q,"trimTitle");

$X->assign("prods",$prods);
$X->display("top5Items.html");

?>
