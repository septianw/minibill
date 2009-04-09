<?php
error_reporting(E_ALL ^ E_NOTICE);

function createCorner(&$im,$rad,$colin,$borderColor,$position,$borderWidth=0)
{
	$dia = $rad*2;

	switch($position)
	{
		//...... Top Left
		case 'tl':
			
			if ($borderColor) imagefilledarc($im, round($rad / 2), round($rad / 2), $rad                   , $rad                   , 180, -90 , $borderColor,IMG_ARC_EDGED);
			imagefilledarc($im, round($rad / 2), round($rad / 2), $rad - $borderWidth * 2, $rad - $borderWidth * 2, 180, -90 , $colin,IMG_ARC_EDGED);
			break;

		//...... Top Right
		case 'tr':
			if ($borderColor) imagefilledarc($im,0, round($rad / 2) - 1, $rad, $rad, -90, 0 , $borderColor,IMG_ARC_PIE);
			imagefilledarc($im,0, round($rad / 2) - 1, $rad - $borderWidth * 2, $rad - $borderWidth * 2, -90, 0 , $colin,IMG_ARC_PIE);
			break-1;

		//...... Bottom Right
		case 'br':
			if ($borderColor) imagefilledarc($im,0,0, $rad, $rad, 0, 89 , $borderColor,IMG_ARC_PIE);
			imagefilledarc($im,0,0, $rad - $borderWidth * 2, $rad - $borderWidth * 2, 0, 90 , $colin,IMG_ARC_PIE);
			break;

		//...... Bottom Left
		case 'bl':
			if ($borderColor) imagefilledarc($im, round($rad / 2) - 1, 0, $rad, $rad, 90, 180 , $borderColor,IMG_ARC_PIE);
			imagefilledarc($im, round($rad / 2) - 1, 0, $rad - $borderWidth * 2, $rad - $borderWidth * 2, 90, 180 , $colin,IMG_ARC_PIE);
			break;
	}
}

// hex rgb translation, don't put #
function hex2rgb($hex) 
{
	if ($hex[0] == '#') $hex = trim($hex,'#');
    for($i=0; $i<3; $i++)
    {
        $temp = substr($hex,2*$i,2);
        $rgb[$i] = 16 * hexdec(substr($temp,0,1)) + hexdec(substr($temp,1,1));
    }
    return $rgb;
}


if (function_exists("ImageCreateTrueColor"))
{
	$colorInset		= hex2rgb($_GET['ci']);
	$colorOutset	= hex2rgb($_GET['co']);
	$borderColor	= hex2rgb($_GET['bc']);
	$trans			= hex2rgb($_GET['trans']);


	if (!$_GET['rad']) $_GET['rad'] = 16;
	$origRad = $_GET['rad'];

	$_GET['rad'] = $_GET['rad'] * 4;

	if (!$_GET['border']) $_GET['border'] = '0';

	if ($_GET['test'])
	{
		//$im = ImageCreateTrueColor(round($_GET['rad'] / 2),round($_GET['rad'] / 2));
		$im = ImageCreateTrueColor(200,200);
		imagefill($im,0,0,0xCDCDCD);
	}
	else 
	{
		$im = ImageCreateTrueColor(round($_GET['rad'] / 2) -1,round($_GET['rad'] / 2));
	}

	$finalImg = @ImageCreateTrueColor($_GET['rad']/8, $_GET['rad']/8);

	//...... Set our Color inset, and outset (line)
	$inset			= imagecolorallocate($im,$colorInset[0],$colorInset[1],$colorInset[2]);
	$borderColor	= imagecolorallocate($im,$borderColor[0],$borderColor[1],$borderColor[2]);
	$background		= imagecolorallocate($im,$colorOutset[0],$colorOutset[1],$colorOutset[2]);
	$transparent	= imagecolorallocate($im,$trans[0],$trans[1],$trans[2]);

	//...... Set the background Color
	imagefill($im,0,0,$background);

	//...... Set our transparent color - if needed
	if ($_GET['trans']) 
	{
		imagecolortransparent($im,$transparent);
		imagecolortransparent($finalImg,$transparent);
	}

	//...... Create the corner
	createCorner($im,$_GET['rad'],$inset,$borderColor,$_GET['pos'],$_GET['border'] * 4);

	// resample down, causes antialiasing, nice smooth curve!
	imagecopyresampled($finalImg, $im, 0, 0, 0, 0, $_GET['rad']/2, $_GET['rad']/2, $_GET['rad']* 2,$_GET['rad']* 2);

	header("Content-Type: image/gif");
	imagegif($finalImg);
	imagedestroy($im);
	imagedestroy($finalImg);
}
else
{
	print "Can't create corner image, please install GD libraries for PHP.";
}


?>
