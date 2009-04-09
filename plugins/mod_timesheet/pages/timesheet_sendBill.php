<?php


include($plugin['mod_timesheet']['timesheet_dir'].'include/timesheet.class.php');

$ts = new Timesheet($plugin['mod_timesheet']['timesheet_db']);
$client = $ts->getClientStats($_REQUEST['client_id']);

$Q="SELECT * FROM users,mod_timesheet WHERE ts_id=$_REQUEST[client_id] AND id=mb_id";
$mbInfo = mysql_fetch_assoc(mysql_query($Q));
$client['mb'] = $mbInfo;


if (!$mbInfo['id']) 
{
	header("Location: index.php?page=timesheet_clientInfo&client_id=$_REQUEST[client_id]");
}

$client['info'] = $ts->getClientInfo($_REQUEST['client_id']);
$jobInfo   = $ts->getClientJobs($_REQUEST['client_id'],1);

if ($jobInfo)
{
	foreach($jobInfo as $idx=>$job)
	{
		$tasks[$job[id]]	= $ts->getTaskList($job['id']);
		$jobs[$job[id]]		= $jobInfo[$idx];
	}
}

/*
print "<pre>";
print_r($client);
print_r($tasks);
print_r($jobs);
print "</pre>";
*/

$X->assign('mbInfo',$mbInfo);
$X->assign('client',$client);
$X->assign('jobs',$jobs);
$X->assign('tasks',$tasks);

//....... Hijack the templates
$hijackTemplate = 1;
$_REQUEST['page'] = $config['plugin_dir'].'mod_timesheet/templates/admin/'.$thisFile;

?>
