<?php

$Q = buildSet($_REQUEST[q],'id','mod_forms_question');
mysql_query($Q);

header("Location: index.php?page=editForm&id=".$_REQUEST['q']['form_id']."&msg=".base64_encode("#00A000|#FFFFFF|Question Saved."));

?>
