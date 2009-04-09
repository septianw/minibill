<?php

$form_id = $_REQUEST['id'];

$Q="SELECT redirect
	FROM mod_forms
	WHERE id='$form_id' LIMIT 1";
$res = mysql_query($Q);
list($redirect_to) = mysql_fetch_assoc($res);

if (!isset($_SESSION['lastUrl']) && !isset($redirect_to)) 
	$redirect_to = "index.php?page=form_complete";
else
	$redirect_to = (strlen($redirect_to)) ? $redirect_to : $_SESSION['lastUrl'];

unset($_SESSION['answers']);
unset($_SESSION['myPrice']);
unset($_SESSION['totalPrice']);
unset($_SESSION['form_id']);
unset($_SESSION['form_index']);
unset($_SESSION['form_item']);

session_write_close();
header("Location: $redirect_to");

exit();

?>
