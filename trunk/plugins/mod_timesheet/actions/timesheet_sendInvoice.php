<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the GPL License                       */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

//...... Pulls my variables for my smarty template
include($config['plugin_dir'].'mod_timesheet/pages/timesheet_sendBill.php');


foreach($tasks as $job_id=>$idx)
{
	$ts->setJobLastBilled(date('Y-m-d h:i:s',time()),$job_id);

	/* This doesn't go here.
	//...... Do this when the bill is paid
	foreach($idx as $task)
	{
		$ts->archiveTask($task['id']);
	}
	*/
}

$o = new OrderClass($client['mb']['id']);

//...... Find out if we have the "Project" item, if not set one up
$Q="SELECT * FROM products WHERE title='Project' LIMIT 1";
$prod = mysql_fetch_assoc(mysql_query($Q));

if (!$prod)
{
	$Q="INSERT INTO products SET title='Project',item_desc='Time and Billing',stock='-1',is_recurring='no'";
	mysql_query($Q);

	$prod['id'] = mysql_insert_id();
}

$o->addItem($prod['id'],1,$client['revenue']);
$uniq_id = $o->postOrder();

//...... Set this order group status to "DUE"
$Q="UPDATE orders SET status='due' WHERE uniq_id='$uniq_id'";
mysql_query($Q);

#print_r($client);
#print_r($tasks);
#print_r($jobs);

//..... Convert tasks to product listing
$x = 0;
foreach($jobs as $job_id=>$job)
{
	$items[$x]['item_desc'] = $job['jobDesc'];
	$items[$x]['price']		= number_format($job['revenue'],2);
	$grand_total += $job['revenue'];
	$x++;
}

//...... Set the price of the product for the template
$prod['price'] = $client['revenue'];

$X->assign('uniq_id',$uniq_id);
$X->assign('user',$client['mb']);
$X->assign('prod_list',$prod['id']);
$X->assign('items',$items);
$X->assign('grand_total',number_format($grand_total,2));

$msg = stripslashes($X->fetch("db:".$config[invoice][template]));

print $msg;
exit();

//...... Stephen Lawrence email contribution
print send_mail($config['invoice']['friendly'],$config['invoice']['email'],
			$client['mb']['firstname'].' '.$client['mb']['lastname'],
			$client['mb']['email'],
			$config['company']['name']." Invoice",stripslashes($msg),'html');


//...... send_mail Function contributed by Stephen Lawrence
function send_mail($myname, $myemail, $contactname, $contactemail,
$subject, $message, $type='plain') {
 $headers .= "MIME-Version: 1.0\n";
 $headers .= "Content-type: text/$type; charset=iso-8859-1\n";
 $headers .= "From: \"".$myname."\" <".$myemail.">\n";

 return(mail("\"".$contactname."\" <".$contactemail.">", $subject,
$message, $headers));
}

header("Location: index.php?page=timesheet&msg=".base64_encode('Invoice Sent'));

?>
