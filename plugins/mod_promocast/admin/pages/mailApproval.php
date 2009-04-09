<?php
$hijackTemplate =1;

$TITLE="Messages awaiting approval";

$Q="SELECT mc_id,q_id,q_subject,q_sender_name,q_sender_email,q_u_id,mc_send_date
	FROM 
		promocast.mailer_campaign,promocast.mailer_campaign_data,promocast.mailer_content 
	WHERE 
		mc_id=mcd_id 
	AND 
		mc_mailer_id=q_id 
	AND 
		mc_status=100";

$res = mysql_query($Q);
print mysql_error();
while($message = mysql_fetch_assoc($res))
{
	$messages[] = $message;
}

$X->assign('messages',$messages);

?>
