<?php
$fileDir = $config['mod_cart']['image_dir'];

$extension[] = '.gif';
$extension[] = '.jpg';
$extension[] = '.jpeg';
$extension[] = '.png';


if (is_dir($fileDir))
{
	
	foreach($extension as $ext)
	{
		$tmp = "largeimage_".$_GET['id'].$ext;

		if (file_exists($fileDir.$tmp))
		{
			$file = stat($fileDir.$tmp);
			$file['56k'] = number_format(($file['size'] * 8) / 48000);
			$file['sizeF'] = format_filesize($file['size']);
			$file['name'] = $tmp;
			$largeImage = $file;
			break;
		}
	}

	foreach($extension as $ext)
	{
		$tmp = "thumbnail_".$_GET['id'].$ext;
		if (file_exists($fileDir.$tmp))
		{
			$file = stat($fileDir.$tmp);
			$file['56k'] = number_format(($file['size'] * 8) / 48000);
			$file['sizeF'] = format_filesize($file['size']);
			$file['name'] = $tmp;
			$thumbFile = $file;
			break;
		}
	}
}
else
{
	$sysMsg->addMessage("<b>$thisPlugin:</b><br />Cannot find images - $fileDir (png,jpg,jpeg,gif)");
}

$plugin[$thisPlugin][$thisFile]['pos']      = 'below';

$X->assign('largeImage',$largeImage);
$X->assign('thumbNail',$thumbFile);

?>
