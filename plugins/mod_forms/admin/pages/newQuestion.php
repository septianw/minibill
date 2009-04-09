<?php

$Q="SHOW COLUMNS FROM mod_forms_question LIKE 'q_type'";
$res=mysql_query($Q);
$types=mysql_fetch_row($res);
$q_types=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$types[1]));
print mysql_error();

$Q="SHOW COLUMNS FROM mod_forms_question LIKE 'q_isRequired'";
$res=mysql_query($Q);
$types=mysql_fetch_row($res);
$q_isRequired=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$types[1]));

$Q="SELECT * FROM mod_forms_question WHERE id='$_REQUEST[id]' LIMIT 1";
$res = mysql_query($Q);
$question = mysql_fetch_assoc($res);
if (isset($_REQUEST['form_id'])) $question['form_id'] = $_REQUEST['form_id'];

$question['q_caption']	= htmlentities(stripslashes($question['q_caption']));
$question['q_values']	= htmlentities(stripslashes($question['q_values']));
$question['q_errortext']= htmlentities(stripslashes($question['q_errortext']));
$question['q_preg']		= htmlentities(stripslashes($question['q_preg']));
$question['q_style']	= htmlentities(stripslashes($question['q_style']));

//print_pre($question);

$X->assign('q',$question);
$X->assign('q_types',$q_types);
$X->assign('q_isRequired',$q_isRequired);

//....... Hijact the templates
$hijackTemplate = 1;
$thisPage = $config['plugin_dir'].$pluginName.'/templates/admin/'.$thisPage;

?>
