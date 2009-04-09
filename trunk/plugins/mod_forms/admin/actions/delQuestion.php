<?php

$Q="DELETE FROM mod_forms_question WHERE id='$_REQUEST[id]' LIMIT 1";
mysql_query($Q);

$redirect_to = "index.php?page=editForm&id=".$_REQUEST['form_id']."&msg=".base64_encode("#00A000|#FFFFFF|Question DELETED!");

?>
