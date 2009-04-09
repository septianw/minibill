<?php
global $data;

print "* Cancelling file download subscription\n";
foreach ($data as $user_id => $val)
{

	foreach($data[$user_id]['items'] as $num=>$item)
	{
		$Q="SELECT file_id FROM mod_download WHERE product_id='{$item['product_id']}'";
		list($files[]) = mysql_fetch_row(mysql_query($Q));
		print_r($files);
	}

	foreach($files as $file_id)
	{
		$Q="DELETE FROM mod_download_data WHERE user_id='$user_id' AND file_id='$file_id'";
		mysql_query($Q);
		print mysql_error();
	}
}

?>
