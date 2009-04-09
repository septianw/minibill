<?php
define('LOADED',1);
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the GPL License (Contribute or Pay!) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

error_reporting(E_ALL ^ E_NOTICE);

//..... We need to be in a workable directory
chdir($argv[1]);

include("config.php");
include($config['include_dir']."dbConnect.php");
include($config['include_dir']."encryption.php");
include($config['include_dir']."functions.php");
include($config['include_dir']."Smarty.class.php");
include($config['include_dir']."initPlugins.php");

$X = new Smarty();
$X->template_dir    =  $config['template_dir'];
$X->compile_dir     =  $config['template_cache_dir'];
$X->compile_id		=  'invoice';
include($config['include_dir'].'smarty_db_handler.php');

$X->assign('config',$config);

//...... Send invoice this many days in advance to billing
// Eventually load this from a config, default 3, 0 = disable invoice sending
if ($config['invoice']['send_advance'] == 0) 
{
	print "* Invoicing disabled *\n";
	exit();
}
$days_in_advance = $config['invoice']['send_advance']; 

$today = time();

$invoice_date = date('Y-m-d',$today + (86400 * $days_in_advance));
print "* Invoicing for $invoice_date\n\n";

$Q="SELECT * FROM orders,user 
	WHERE payment_due='$invoice_date' 
	AND recurring > 0
	AND status IN('paid','due','suspend')
	AND user.id=orders.user_id 
	ORDER BY uniq_id"; // AND paid='n' ? no, because we have to change the 'y' to 'n'

$res = mysql_query($Q);

//...... If we have any to invoice today
if (mysql_num_rows($res))
{
	//...... Go through each record
	while($info = mysql_fetch_assoc($res))
	{
		$user_id = $info['user_id'];
		if (!isset($data[$user_id]))
		{
			$Q="SELECT * FROM user WHERE id='$info[user_id]' LIMIT 1";
			$user = mysql_fetch_assoc(mysql_query($Q));
			$data[$user_id]['userinfo'] = $user;
		}
		$data[$user_id][items][] = $info;
	}
	//...... Send invoice via EMAIL (yay ..)
	foreach ($data as $user_id => $val)
	{
		$fname = ucfirst($data[$user_id]['userinfo']['firstname']);
		$lname = ucfirst($data[$user_id]['userinfo']['lastname']);
		$email = ucfirst($data[$user_id]['userinfo']['email']);
		$items = count($data[$user_id][items]);
		$totals = 0;

		//...... Get item totals
		foreach($data[$user_id]['items'] as $i=>$v)
		{
			$Q="SELECT title,category_id FROM products WHERE id='{$v['product_id']}'";
			list($desc,$cat_id) = mysql_fetch_row(mysql_query($Q));
			$data[$user_id]['items'][$i]['desc'] = $desc;
			$totals += $v['amount'];
		}
		$totals = number_format($totals,2);

		print "** Sending Invoice To: $fname $lname\n";
		print "*** $items items on this invoice totalling $totals\n\n";

		$X->assign('days_in_advance',$days_in_advance);
		$X->assign('charge_date',$data[$user_id]['items'][0]['payment_due']);
		$X->assign('grand_total',$totals);
		$X->assign('items',$data[$user_id]['items']);
		$X->assign('user',$data[$user_id]['userinfo']);
		$X->assign('fname',$fname);
		$X->assign('lname',$lname);

		$Q="SELECT invoice_template FROM category WHERE category_id='$cat_id'";
		list($template) = mysql_fetch_row(mysql_query($Q)); 

		if (strlen($template))
		{
			$msg = stripslashes($X->fetch("db:$template"));
			//...... Send friendly invoice message
			mail("$email","Billing Invoice Notice",$msg,"From: ".$config['invoice']['friendly']." <".$config['invoice']['email'].">\nContent-Type: text/html");
			print "Invoice email sent.\n";
		}
	}
}
else
{
	print "* Nobody to send invoices to today.\n";
}

?>
