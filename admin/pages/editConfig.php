<?php 

$X->assign('title','CONFIGURATION');

$Q="SELECT id,variable,value FROM config WHERE id='$_GET[id]' ORDER BY variable";
$res = mysql_query($Q);

$confvars = array();

while($confvar = mysql_fetch_assoc($res))
{
	$confvars[] = $confvar;
}

$X->assign('dbconfig',$confvars);
$X->assign('confid',$_GET[id]);


?>
