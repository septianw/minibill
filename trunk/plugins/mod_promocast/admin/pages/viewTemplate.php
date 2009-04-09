<?php
$hijackTemplate = 1;

$Q="SELECT * FROM promocast.template_cats";
$res = mysql_query($Q);
while($cat = mysql_fetch_assoc($res)) { $cats[] = $cat;}


$Q="SELECT 
		*
	FROM 
		promocast.template_data
	WHERE 
		id='{$_REQUEST['id']}'
	LIMIT 1";

$info = mysql_fetch_assoc(mysql_query($Q));
print mysql_error();

$Q="SELECT id,company FROM users";
$res = mysql_query($Q);
while($user = mysql_fetch_assoc($res))
{
    $users[] = $user;
}

$X->assign('users',$users);
$X->assign('tpl',$info);
$X->assign('cats',$cats);

?>
