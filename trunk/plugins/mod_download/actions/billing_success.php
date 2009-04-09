<?php

global $data;

print "* Enabling file downloads\n";

//print_pre($data);

foreach ($data as $user_id => $val)
{
    foreach($data[$user_id]['items'] as $num=>$item)
    {
        $Q="SELECT file_id FROM mod_download WHERE product_id='{$item['product_id']}'";
		$dlres = mysql_query($Q);
		while($file = mysql_fetch_assoc($dlres))
		{
			$files[] = $file;
		}
    }

	if (is_array($files))
	{
		foreach($files as $file)
		{
			$Q="UPDATE mod_download_data SET hidden='0' WHERE user_id='$user_id' AND file_id='$file[file_id]'";
			mysql_query($Q);
			print mysql_error();
		}
	}
}

?>
