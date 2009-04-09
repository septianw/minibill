<?php

$hijackTemplate=1;

if (isset($_REQUEST['search']))
{
	$likeEQ="=";
	//if ($_REQUEST['search']['by'] == 'mc_send_date')
	{
		$likeEQ=" LIKE ";	
		$_REQUEST['search']['value'] .= '%';
	}
	$Q="SELECT * FROM
			promocast.mailer_campaign,promocast.mailer_content
		WHERE
			{$_REQUEST['search']['by']}$likeEQ\"{$_REQUEST['search']['value']}\"
		AND
			mc_status=0
		AND 
			mc_mailer_id=q_id
		ORDER BY
			mc_send_date
		ASC";
}
else
{
	$Q="SELECT * 
		FROM 
			promocast.mailer_campaign,
			promocast.mailer_content 
		WHERE 
			mc_mailer_id=q_id 
		AND 
			mc_status=0 
		ORDER BY 
			mc_send_date 
		ASC";
}

$res = mysql_query($Q);
while($msg = mysql_fetch_assoc($res))
{
	$messages[] = $msg;
}

$X->assign('messages',$messages);

?>
