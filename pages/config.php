<?php


$Q="SELECT DISTINCT(id) FROM config";
$res = mysql_query($Q);
print mysql_error();

while($id = mysql_fetch_assoc($res))
{
	$configIds[] = $id;
}

?>
