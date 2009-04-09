<?php

$X->assign('title',"MINIBILL CONFIG");

$Q="SELECT DISTINCT(id) FROM config";
$res = mysql_query($Q);
print mysql_error();

$c = 0;
while($id = mysql_fetch_assoc($res))
{
	$Q="SELECT value FROM config WHERE id='desc' AND variable='$id[id]' LIMIT 1";
	list($desc) = mysql_fetch_row(mysql_query($Q));
	$configIDs[$c][id] = $id[id];
	$configIDs[$c][desc] = $desc;
	$c++;
}
$X->assign('configIDs',$configIDs);

?>
