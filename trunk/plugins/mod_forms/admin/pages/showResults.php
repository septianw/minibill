<?php

$Q="SELECT form.name AS form_name,form.id AS form_id,user_id,client.email,sum(price) AS price,answer_date FROM mod_forms,users,mod_forms_answer WHERE user_id=client.id AND form_id=form.id GROUP BY client.email";
$results = getResults($Q);

$X->assign('info',$results);


?>
