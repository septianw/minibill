<?php
/*
Array
(
    [thumbNail] => Array
        (
            [name] => jayson2_mod2.jpg
            [type] => image/jpeg
            [tmp_name] => /tmp/phpkXjiad
            [error] => 0
            [size] => 344678
        )

    [largeFile] => Array
        (
            [name] => 
            [type] => 
            [tmp_name] => 
            [error] => 4
            [size] => 0
        )

)
*/

$uploaddir = $config['mod_cart']['image_dir'];

$extension[] = 'jpg';
$extension[] = 'jpeg';
$extension[] = 'png';
$extension[] = 'gif';

foreach($_FILES as $type=>$file)
{
	if ($file['name'])
	{
		$type = strtolower($type);
		$sansExt = $uploaddir . $type."_".$_REQUEST['product_id'];

		//...... If you have problems with image extensions, this is probably why.
		$fileExt = trim(strtolower(substr($file['name'],-3,3)));

		if (preg_match("/jpg|jpeg|gif|png/i",$fileExt))
		{
			$uploadFile = "$sansExt.$fileExt";

			if (move_uploaded_file($file['tmp_name'], $uploadFile))
			{
				$msg .= "&msg[]=".base64_encode("$type Upload Successful!");

				//...... Removes other files
				foreach($extension as $ext)
					if ($fileExt != $ext)
						@unlink("$sansExt.$ext");
			}
			else
			{
				$msg .= "&msg[]=".base64_encode("$type Upload Failed!");
			}
		}
		else
		{
			$msg = "&msg=".base64_encode("$type Invalid image type: $fileExt");
		}
		$redirect_to = ("index.php?page=editProduct&id=$_REQUEST[product_id]".$msg);
	}
}
?>
