<?php

include($plugin['mod_timesheet']['timesheet_dir'].'include/timesheet.class.php');
$ts = new Timesheet($plugin['mod_timesheet']['timesheet_db']);

$info = $ts->getClientInfo($_REQUEST['client_id']);

$Q="SELECT * FROM users,mod_timesheet WHERE ts_id=$_REQUEST[client_id] AND id=mb_id";
$client = mysql_fetch_assoc(mysql_query($Q));

$client['info'] = $info;
$client['ts_id'] = $_REQUEST['client_id'];

$X->assign('client',$client);

//....... Hijack the templates
$hijackTemplate = 1;
$_REQUEST['page'] = $config['plugin_dir'].'mod_timesheet/templates/admin/timesheet_clientInfo'

?>
