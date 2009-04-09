<?php

$Q="SELECT item_code,id FROM products";
$res = mysql_query($Q);

while($info =mysql_fetch_assoc($res))
{
	$Q="INSERT INTO mod_interface_prodLookup VALUES('{$info['id']}','{$info['item_code']}','REMOTE_KEY')";
	print $Q;
	mysql_query($Q);
	print mysql_error();
}

//$redirect_to = "index.php?page=products";

?>
