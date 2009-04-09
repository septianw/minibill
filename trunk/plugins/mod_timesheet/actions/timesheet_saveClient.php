<?php


//...... TimeSheet client ID
$ts_id = $_REQUEST['client']['ts_id'];
unset($_REQUEST['client']['ts_id']);

if ($_REQUEST['client']['mb_id']) 
{
	$_REQUEST['client']['id'] = $_REQUEST['client']['mb_id'];
	unset($_REQUEST['client']['mb_id']);
}

//...... set the update row if we have a client mb_id
$update = ($_REQUEST['client']['id'] > 0) ? 'id' : '';


//...... Generate the query from the received data
$Q= buildSet($_REQUEST['client'],$update,'users');
mysql_query($Q);
print mysql_error();

//...... Add new lookup table entry
if (!isset($_REQUEST['client']['id']))
{
	//...... Gets my MINIBILL ID
	$mb_id = mysql_insert_id();
	$Q="INSERT INTO mod_timesheet VALUES($mb_id,$ts_id)";
	mysql_query($Q);
	print mysql_error();
}

//header redirect
header("Location: index.php?page=timesheet_sendBill&client_id=$ts_id");

?>
