<?php

$Q="SELECT * FROM users WHERE id='$_SESSION[id]'";
$res = mysql_query($Q);
$client = mysql_fetch_assoc($res);


$Q="SELECT id,q_caption,answer,price FROM mod_forms_question question,mod_forms_answer answer WHERE question.id=answer.question_id AND answer.form_id='$_REQUEST[form_id]' and answer.user_id='$_SESSION[id]' GROUP BY answer ORDER BY q_order;";
$res = mysql_query($Q);

while ($info = mysql_fetch_assoc($res))
{
	$info['q_caption'] = htmlentities(stripslashes($info['q_caption']));
	$answer[] = $info;
	$total_price += $info['price'];
}

$X->assign('client',$client);
$X->assign('answer',$answer);
$X->assign('total_price',number_format($total_price));

//....... Hijact the templates
$hijackTemplate = 1;
$thisPage = $config['plugin_dir'].$pluginName.'/templates/admin/'.$thisPage;

?>
