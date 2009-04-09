<?php

$Q="SELECT name FROM mod_forms WHERE id='$_REQUEST[id]' LIMIT 1";
list($formName) = mysql_fetch_row(mysql_query($Q));
$X->assign('formName',$formName);

$Q="SELECT * FROM mod_forms_question WHERE form_id='$_REQUEST[id]' ORDER BY q_order";
$res = mysql_query($Q);
while($question = mysql_fetch_assoc($res))
{
	$question['q_caption'] = htmlentities(stripslashes($question['q_caption']));
	$question['q_values'] = htmlentities(stripslashes($question['q_values']));
	$questions[] = $question;
}
$X->assign('id',$_REQUEST['id']);
$X->assign('questions',$questions);
$X->assign('title','Order form builder');

//....... Hijact the templates
$hijackTemplate = 1;
$thisPage = $config['plugin_dir'].$pluginName.'/templates/admin/'.$thisPage;


?>
