<?php

$name			= 'mod_timesheet';
$timesheetDB	= 'ultrize_timecard';
$enabled		= 'true';
$version		= '1.0';
$author			= 'Matthew Frederico';
$license		= 'Commercial';

//create the ultimate plugin array
$plugin[$name] = array(	'enabled'	=>$enabled,
						'timesheet_db'=>$timesheetDB,
						'timesheet_dir'=>"/home/ultrize/www/timesheet/timesheet/",
						'version'	=>$version,
						'license'	=>$license,
						'author'	=>$author);


?>
