<?php

$list = "{$_REQUEST['file']}";
$fd = fopen($list,"r");
$fields = fgetcsv($fd,2048);

$Q="SHOW COLUMNS FROM products";
$res = mysql_query($Q);
while($col = mysql_fetch_assoc($res))
{
	$myCol = $col['Field'];
	$cols[$col['Field']] = ucfirst($myCol);
}

$X->assign('fields',$fields);
$X->assign('cols',$cols);

?>
