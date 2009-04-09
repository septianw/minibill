<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

//...... fixes php vulnerability in demo mode
if ($DEMO_MODE)
{
	$_POST['template']['content'] = preg_replace("/{PHP}(.*){\/PHP}/sim",'<!-- PHP CODE STRIPPED FOR YOUR SAFETY, HAVE A NICE DAY -->',$_POST['template']['content']);
}

$_POST['template']['stamp'] = time();
$Q=buildSet($_POST['template'],'id','templates');

/*
$Q="REPLACE INTO templates SET 
	title='".addslashes($_POST['template']['title'])."',
	stamp=UNIX_TIMESTAMP(),
	content='".addslashes($_POST['template']['content'])."'";
*/

mysql_query($Q);
$msg = base64_encode("#00A000|#FFFFFF|Template Saved");
header("Location: index.php?msg=$msg&page=templates");

?>
