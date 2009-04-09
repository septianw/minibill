<?php
/***********************************************************/
/* Handle passed Messages                                  */
/* Message format                                          */
/*    base64_encode("background|foreground|text message"); */
/***********************************************************/
class Messages
{
	var $img_path = '';
	var $messages = Array();
	var $defaultFG = '';
	var $defaultBG = '';

	function Messages($FG='#000000',$BG='#CDCDCD')
	{
		$this->defaultFG = $FG;
		$this->defaultBG = $BG;
	}

	function setImgPath($img_path = 'img/')
	{
		$this->img_path = $img_path;
	}

	function handleRequest($requestVar)
	{
		if (is_array($requestVar))
		{
			foreach($requestVar as $msg)
			{
				$this->decodeMsg($msg);
			}
		}
		else
		{
			$this->decodeMsg($requestVar);
		}
	}


	function addMessage($str,$fg="#000000",$bg="#CDCDCD",$delay=10,$radius=15,$border=0)
	{
		global $messages;

		$final['color']		= $fg;
		$final['bgcolor']	= $bg;
		$final['msg']		= $str;
		$final['rad']		= $radius;
		$final['border']	= $border;
		$final['delay']		= $delay;	
		$final['outline']	= $fg;

		$final['img'] = ($this->img_path.'corner.php');

		if (strlen($str)) $this->messages[] = $final;
	}

	function decodeMsg($msg)
	{

		$myMsg = base64_decode($msg);
		if (preg_match("/\|/",$myMsg))
		{
			list($bg,$fg,$msg) = explode('|',$myMsg);
			$this->addMessage($msg,$fg,$bg);
		}
		else $this->addMessage($myMsg,$this->defaultFG,$this->defaultBG);
	}
}

?>
