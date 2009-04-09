<?php

$Q="DELETE FROM mod_forms_question WHERE form_id='$_REQUEST[id]'";
mysql_query($Q);

$Q="DELETE FROM mod_forms WHERE id='$_REQUEST[id]' LIMIT 1";
mysql_query($Q);

header("Location: index.php?page=forms&msg=".base64_encode("#00A000|#FFFFFF|Form has been deleted."));

?>
