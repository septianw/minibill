<?php

foreach($_SESSION['prods'] as $prod)
{
	$Q="SELECT file_id FROM mod_download WHERE product_id='{$prod['id']}'";
	$res = mysql_query($Q);
	while(list($file_id) = mysql_fetch_row($res))
	{
		$Q="REPLACE INTO mod_download_data VALUES('$file_id','{$_SESSION['id']}','0','0000-00-00 00:00:00',NOW(),0)";
		mysql_query($Q);
		if (mysql_error()) print $Q.mysql_error();
	}
}
?>
