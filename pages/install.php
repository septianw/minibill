<?php
$thisPage = 'install';

$Q="SELECT * FROM config WHERE id='mbadmin'";

if (!$res = @mysql_query($Q)) $sysMsg->addMessage("<div align=center><b>There is currently a problem with your mysql settings, please double check and try again!</b></div>");
$X->assign('title','INSTALLING');

list($is_installed) = (!mysql_errno());

if (($is_installed > 1) ? 1 : 0)
{
	header("Location: index.php?page=index&msg=".base64_encode("#CDCDCD|#000000|Already installed, thank you!"));
	exit();
}

if (!is_writable($config['template_cache_dir']) || !is_readable($config['template_cache_dir']))
{
	print "<h1>Installation Note:</h1>";
	print "<h3>Please make sure you do the following:<Br />";
	print "chmod 777 config.php<br />";
	print "chmod 777 ".$config['template_cache_dir']."<br /></br>";
	print "<a href='#refresh' onclick='window.location.reload()'>Then click here.</a></h3>";
	exit();
}

include("include/readConf.class.php");

$c = new ReadConf('config.php');
$c->getVars();

$X->assign('installDesc',$c->comments);
$X->assign('installConfig',$c->variables);

?>
