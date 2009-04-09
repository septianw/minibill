<?php
$Q="DELETE FROM mod_forms_answer WHERE client_id='$_GET[client_id]' AND form_id='$_GET[form_id]'";
mysql_query($Q);

$redirect_to = "Location: index.php?page=showResults";
?>
