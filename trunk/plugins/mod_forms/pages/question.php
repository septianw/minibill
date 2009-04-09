<?php

$_GET['id'] = ($_GET[id] > 0) ? $_GET[id] : 1;

$Q="SELECT * FROM mod_forms WHERE id='$_GET[id]' LIMIT 1";
$res = mysql_query($Q);
$form = mysql_fetch_assoc($res);

$form['form_blurb'] = stripslashes($form['form_blurb']);
$form['form_heading'] = stripslashes($form['form_heading']);

$X->assign('form',$form);

?>
