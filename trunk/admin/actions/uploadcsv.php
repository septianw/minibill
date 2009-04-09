<?php
set_time_limit(0);
ignore_user_abort (true);

	if (is_uploaded_file($_FILES['csvfile']['tmp_name'])) 
	{
		$fileName = $_FILES['csvfile']['name'];
		$realFile = "{$config['global']['upload_dir']}/{$_FILES['csvfile']['name']}";
		move_uploaded_file($_FILES['csvfile']['tmp_name'],$realFile); 

		//...... Unzip list file if needed
		$resulting_file = decompress($realFile);

		//...... Unlinks .zip file
		//if ($resulting_files != $_FILES['csvfile']['name']) unlink("$realFile");

		$redirect_to = "index.php?page=importCsv&file=$resulting_file";
	} 
	else
	{
		print 'Upload Failed.';
	}
	


?>
