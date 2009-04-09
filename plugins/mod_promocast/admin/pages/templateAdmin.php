<?php

$hijackTemplate =1;

$Q="SELECT 
		* 
	FROM 
		promocast.template_cats";
$res = mysql_query($Q);

while($cat = mysql_fetch_assoc($res))
{

	$Q="SELECT 
			id,title,active,cat_id 
		FROM 
			promocast.template_data 
		WHERE 
			cat_id='{$cat['id']}'";


	$res2 = mysql_query($Q);
	while($tpl = mysql_fetch_assoc($res2)) $cats["{$cat['id']}"]['info'][] = $tpl;
	
	$cats["{$cat['id']}"]['title'] = $cat['title'];
	$cats["{$cat['id']}"]['count'] = count($cats["{$cat['id']}"]['info']);
}

$X->assign('cats',$cats);

?>
