<?php

if ($_REQUEST['afid'])
{
	setCookie('afid',$_REQUEST['afid'],time() + (86400 * 365));
}

?>
