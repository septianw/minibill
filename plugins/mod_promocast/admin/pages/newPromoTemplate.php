<?php
$hijackTemplate = 1;

$Q="SELECT * FROM promocast.template_cats";
$res = mysql_query($Q);
while($cat = mysql_fetch_assoc($res)) { $cats[] = $cat;}

$X->assign('cats',$cats);

?>
