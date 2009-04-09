<?php

include($plugin['mod_timesheet']['timesheet_dir'].'include/timesheet.class.php');
$ts = new Timesheet($plugin['mod_timesheet']['timesheet_db']);

$client['info']= $ts->getClientInfo($_REQUEST['client_id']);
$client['ts_id'] = $_REQUEST['client_id'];

$X->assign('client',$client);

//....... Hijack the templates
$hijackTemplate = 1;
$_REQUEST['page'] = $config['plugin_dir'].'mod_timesheet/templates/admin/'.$thisFile;

?>
