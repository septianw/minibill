<?php

function createCorner(&$im,$rad,$colin,$borderColor,$position,$borderWidth=0)
{
	$dia = $rad*2;

	switch($position)
	{
		//...... Top Left
		case 'tl':
			imagefilledarc($im, round($rad / 2) -1, round($rad / 2) -1, $rad, $rad, 180, -91 , $borderColor,IMG_ARC_EDGED);
			imagefilledarc($im, round($rad / 2) -1, round($rad / 2) -1, $rad - $borderWidth * 2, $rad - $borderWidth * 2, 180, -91 , $colin,IMG_ARC_EDGED);
			break;

		//...... Top Right
		case 'tr':
			imagefilledarc($im,0, round($rad / 2) - 1, $rad, $rad, -90, 0 , $borderColor,IMG_ARC_PIE);
			imagefilledarc($im,0, round($rad / 2) - 1, $rad - $borderWidth * 2, $rad - $borderWidth * 2, -90, 0 , $colin,IMG_ARC_PIE);
			break-1;

		//...... Bottom Right
		case 'br':
			imagefilledarc($im,0,0, $rad, $rad, 0, 89 , $borderColor,IMG_ARC_PIE);
			imagefilledarc($im,0,0, $rad - $borderWidth * 2, $rad - $borderWidth * 2, 0, 90 , $colin,IMG_ARC_PIE);
			break;

		//...... Bottom Left
		case 'bl':
			imagefilledarc($im, round($rad / 2) - 1, 0, $rad, $rad, 90, 180 , $borderColor,IMG_ARC_PIE);
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

	if ($_GET['test'] || $argv[1] == 'test') 
	{
		//$im = ImageCreateTrueColor(round($_GET['rad'] / 2),round($_GET['rad'] / 2));
		$im = ImageCreateTrueColor(200,200);
		imagefill($im,0,0,0xCDCDCD);
	}
	else 
	{
		$im = ImageCreateTrueColor(round($_GET['rad'] / 2),round($_GET['rad'] / 2));
		imagefill($im,0,0,0xFFFFFF);
	}

	//...... Set our Color inset, and outset (line)
	$inset = imagecolorallocate($im,$colorInset[0],$colorInset[1],$colorInset[2]);
	$borderColor = imagecolorallocate($im,$colorOutset[0],$colorOutset[1],$colorOutset[2]);

	//...... Set our transparent color
	$white = imagecolorallocate($im,255,255,255);
	imagecolortransparent($im,$white);

	//...... Create the corner
	createCorner($im,$_GET['rad'],$inset,$borderColor,$_GET['pos'],$_GET['border']);

	header("Content-Type: image/gif");
	imagegif($im);
}
else
{
	print "Can't create corner image, please install GD libraries for PHP.";
}


?>
