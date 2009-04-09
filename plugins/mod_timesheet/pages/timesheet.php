<?php

include($plugin['mod_timesheet']['timesheet_dir'].'include/timesheet.class.php');

$ts = new Timesheet($plugin['mod_timesheet']['timesheet_db']);

$Q="SELECT * FROM {$plugin['mod_timesheet']['timesheet_db']}.clients ORDER BY user_id,clientDesc";

$res = mysql_query($Q);
print mysql_error();
while($client = mysql_fetch_assoc($res))
{
	$client['totals'] = $ts->getClientTotals($client['id']);
	if ($client['totals']['revenue'])
		$clients[] = $client;
}

$X->assign('clients',$clients);

//....... Hijack the templates
$hijackTemplate = 1;
$_REQUEST['page'] = $config['plugin_dir'].'mod_timesheet/templates/admin/'.$thisFile;

?>
