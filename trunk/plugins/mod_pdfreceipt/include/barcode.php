<?php
	define('IMG_FORMAT_PNG',	1);
	define('IMG_FORMAT_JPEG',	2);
	define('IMG_FORMAT_WBMP',	4);
	define('IMG_FORMAT_GIF',	8);

function mkBarCode($text,$path = '',$height=15)
{
	global $config;
	if(strlen($text))
	{
		define('IN_CB',true);

		if(include_once($config['include_dir'].'code128.barcode.php'))
		{
			$color_black = new FColor(0,0,0);
			$color_white = new FColor(255,255,255);

			$myBar = new code128($height,$color_black,$color_white,1,$text,2);
			$im = imagecreatetruecolor(1024,300) or die('Can\'t Initialize the GD Library');
			$color = ImageColorAllocate( $im, 255,255,255);
			imagefill($im,0,0,$color);

			$myBar->draw($im);
			$im2 = imagecreate($myBar->lastX,$myBar->lastY);
			imagecopyresized($im2, $im, 0, 0, 0, 0, $myBar->lastX, $myBar->lastY, $myBar->lastX, $myBar->lastY);

			imagepng($im2,$path.$text.'.png');
		}
		else
		{
			header('Content-type: image/png');
			readfile('error.png');
		}
	}
	else{
		header('Content: image/png');
		readfile('error.png');
	}
}
?>
