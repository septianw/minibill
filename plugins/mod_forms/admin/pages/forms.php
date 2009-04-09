<?php
global $submenu;

$Q="SELECT * FROM mod_forms AS f";
$res = mysql_query($Q);

while($info = mysql_fetch_assoc($res))
{
	//...... Number of questions
	$Q="SELECT COUNT(*) FROM mod_forms_question AS questions WHERE form_id='$info[id]' LIMIT 1";
	list($info['questions']) = mysql_fetch_row(mysql_query($Q));

	//...... Get # of people who filled out form
	$Q="SELECT COUNT(DISTINCT user_id) FROM mod_forms_answer AS takers WHERE form_id='$info[id]'";
	list($info['takers']) = mysql_fetch_row(mysql_query($Q));

	//...... Get the item title
	$Q="SELECT title FROM products WHERE id='$info[product_id]' LIMIT 1";
	list($info['item_title']) = mysql_fetch_row(mysql_query($Q));

	$forms[] = $info;
}

$submenu['newForm'] = 'ADD NEW FORM';
$X->assign('submenu',$submenu);

$X->assign('forms',$forms);
$X->assign('title','Form Listings');

//....... Hijact the templates
$hijackTemplate = 1;
$thisPage = $config['plugin_dir'].$pluginName.'/templates/admin/'.$thisPage;


?>
