<?php
define('LOADED',1);
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the GPL License (Contribute or Pay!)  */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

error_reporting(E_ALL ^ E_NOTICE);

//..... Dev will set "valid" to always return 1
//..... Making each order valid

//...... We need to be in a workable directory.
chdir($argv[1]);

include("config.php");
include($config['include_dir']."dbConnect.php");
include($config['include_dir']."encryption.php");
include($config['include_dir']."functions.php");
include($config['include_dir']."Smarty.class.php");
include($config['include_dir']."order.class.php");
include($config['include_dir']."initPlugins.php");
include($config['include_dir']."payment.class.php");

$X = new Smarty();
$X->template_dir    =  $config['template_dir'];
$X->compile_dir     =  $config['template_cache_dir'];
$X->compile_id		=  'billing';
include($config['include_dir'].'smarty_db_handler.php');

$X->assign('config',$config);

$billing_date = date('Y-m-d');
if(is_on($config['demo']['mode'])) print "* W A R N I N G :    \n\tRunning in demo mode!\n ";

print "************************************\n";
print "* SUSPEND / CANCEL for $billing_date\n";
print "************************************\n";
//...... If pay_fail threshold is set:
if ($config['invoice']['pay_fail'] > 0)
	$or_pay_fail = "OR payfail >= ".$config['invoice']['pay_fail'];

//...... Select pay fils, or cancels, or suspends
$Q="SELECT * FROM orders,users
	WHERE payment_due='$billing_date'
	AND (status IN('cancel','suspend') $or_pay_fail)
	AND users.id=orders.user_id 
	ORDER BY uniq_id";
$res = mysql_query($Q);

if (mysql_num_rows($res))
{
	while($info = mysql_fetch_assoc($res))
	{
		if ($info['payfail'] >= intval($config['invoice']['pay_fail'])) $info['status'] = 'suspend';

		//...... Cancellations and suspensions go here
		if ($info['status'] == 'suspend' || $info['status'] == 'cancel')
		{
			print "* ACCOUNT ".strtoupper($info['status']).": $info[firstname] $info[lastname] : $info[order_id]\n";
			
			//...... If this is canceled, delete it from order history.
			if ($info['status'] == 'cancel')
			{
				$order = new OrderClass($info['user_id'],0,$config);
				$order_count = $order->delOrder($info['order_id']);
				$template = $config['invoice']['cancel_template'];
			}
			else
				$template = $config['invoice']['suspend_template'];

			/*************************************************************/
			/* Loads up the cancellations / suspend scripts feature      */
			/*************************************************************/
			includePlugin('','actions',$info['status'],$X);
			/*************************************************************/

			if (strlen($template))
			{
				if ($info['mailer'] != $info['status'])
				{
					print "- Sending ".strtoupper($info['status'])." notice to: $info[email]\n";
					$msg = stripslashes($X->fetch("db:$template"));

					//...... Send invoice message
					mail("$info[email]","Urgent Notice",$msg,"From: ".$config['invoice']['friendly']." <".$config['invoice']['email'].">\nContent-Type: text/html");

					//...... Copies the admin on the cancellation
					mail($config['invoice']['email'],"Urgent Notice",$msg,"From: $info[email] <$fname $lname>\nContent-Type: text/html");
					print "-> Notice email sent.\n";

					//...... Keep track of which mailer has been sent
					$Q="UPDATE orders SET mailer='$info[status]' WHERE order_id='$info[order_id]'";
					mysql_query($Q);
				}
			}
			else print "- No ".$info['status']." mailer template defined!";
		}
		unset($order);
	}
}
else print "* No suspensions or cancellations today\n";


/***************************************/
/* B I L L I N G - All stuff recurring */
/***************************************/
print "\n************************************\n";
print "* BILLING for $billing_date\n";
print "************************************\n";
$Q="SELECT * FROM orders,products
	WHERE payment_due='$billing_date' 
	AND recurring > 0
	AND status IN('paid','due','suspend')
	AND product_id=products.id
	ORDER BY user_id,uniq_id";

//...... TODO: Check for accounts with payfail > $config[invoice][pay_fail]
//...... suspend them if that criteria is met.

$res = mysql_query($Q);

$full_order_data = array();
//...... If we have any to bill today
if (mysql_num_rows($res))
{
	/*************************/
	/* SET UP THE DATA ARRAY */
	/*************************/
	//...... Go through each record and set up the $full_order_data array.
	//...... in order for us to bill them only one time per order group.
	//...... for the total of their orders
	while($info = mysql_fetch_assoc($res))
	{
		$user_id = $info['user_id'];
		if (!isset($full_order_data[$user_id]))
		{
			$Q="SELECT * FROM users 
				WHERE id='$info[user_id]' 
				LIMIT 1";
			$user = mysql_fetch_assoc(mysql_query($Q));
			$full_order_data[$user_id]['user'] = $user;
		}

		//...... Add their items to their index in the array.
		$full_order_data[$user_id]['items'][] = $info;
	}

	/**********************************************/
	/* NOW GO THROUGH EACH USER IN THE DATA ARRAY */
	/**********************************************/
	foreach ($full_order_data as $user_id => $orderData)
	{

		//...... We've got some uniq id wrangling to do
		if (isset($uniq_id))	unset($uniq_id);
		if (isset($p))			unset($p);

		$uniq_id_list = '';

		//...... Get item amount totals
		foreach($orderData['items'] as $i=>$v)
		{
			//...... Default this to authorize.net non self recurring gateway.
			$gateway = (strlen($v['gateway']) ? $v['gateway'] : 'AUTHNET');
			$gateway_does_recurring = strtolower($v['gateway'])."_auto_recur";

			$amount += $v['amount'] * $v['quantity'];
			$uniq_id[] = $v['uniq_id'];
			$payfail = $v['payfail'];
		}

		$fname = ucfirst($orderData['user']['firstname']);
		$lname = ucfirst($orderData['user']['lastname']);
		$email = ucfirst($orderData['user']['email']);
		$items = count($orderData['items']);

		//..... Build legible uniq id list
		foreach($uniq_id as $uid) $uniq_id_list .= "$uid,";
		$uniq_id_list = rtrim($uniq_id_list,',');

		print "-[$billing_date]-----------------------\n";
		print "# Items : $items \n";
		print "Amount  : ".number_format($amount,2)."\n";
		print "Bill To : $fname $lname\n";
		print "User ID : $user_id\n";
		print "Email   : $email\n";
		print "Uniq ID : $uniq_id_list\n";

		if ($payfail) 
			print "\tNOTE  : Has attempted this charge $payfail / ".$config['invoice']['pay_fail']." times\n";
		print "--- \n";
		
		// ******************
		// CHARGE CARD HERE
		// ******************
		$merchant_id		= strtolower($gateway)."_id";
		$merchant_pass		= strtolower($gateway)."_pass";
		$merchant_referer	= strtolower($gateway)."_referer";

        //.. Instantiate object: service id, merchant_id, merchant_password, test_mode
        $p = new Payment($gateway,$config['merchant'][$merchant_id],$config['merchant'][$merchant_pass],$config['merchant']['test']);
        $p->dbg_level=1;

        //.. Set the http referrer
        $p->set_referrer($merchant_referer);

        //.. Set up the order: customer_id,order_id,amount
        $p->set_order($user_id,$uniq_id_list,$amount);

        //.. Order card info: Name On Card, CC Number, Exp Date, CVV
        $p->set_card_info(  $orderData['user']['firstname'].' '.$orderData['user']['lastname'],
                            data_decrypt($orderData['user']['cardnum'],$config['secret_key']),
                            $orderData['user']['cc_exp_mo'].'/'.$orderData['user']['cc_exp_yr'],
                            $cvv);

        //.. Order Billing Info: firstname, lastname, email, address1, address2, city, state, zip, country,phone
        $p->set_billing_info(   $orderData['user']['firstname'],
                                $orderData['user']['lastname'],
                                $orderData['user']['email'],
                                $orderData['user']['address'],'',
                                $orderData['user']['city'],
                                $orderData['user']['state'],
                                $orderData['user']['zipcode'],
                                $orderData['user']['country'],
                                $orderData['user']['phone']);

        if (!$config['demo']['mode']) $p->send_payment();

        //.. Grab the response
        $valid = $p->response; // Returns 1 or 0

        //.. Send the payment information to gateway
		/*
		print "UNCOMMENT PAYMENT STUFF: ".__LINE__."\n";
		$valid = 1; //..... Comment this out
		*/

		//...... Change this in the configs
		if (is_on($config['demo']['mode'])) $valid = 1;

		//...... Set up template variables.
		$X->assign('days_in_advance',$config['invoice']['send_advance']);
		$X->assign('charge_date',$orderData['items'][0]['payment_due']);
		$X->assign('grand_total',$amount);
		$X->assign('items',$orderData['items']);
		$X->assign('user',$orderData['user']);
		$X->assign('fname',$fname);
		$X->assign('lname',$lname);

		//************************************
		//*        Purchase Failure          *
		//************************************
        if (!$valid)
        {
			$failTotal += $amount;

            //...... Handle authorize.net error messages
			//...... Should probably be doing this some more modular way
			print "DECLINED: ".$p->reason_declined."\n";
            $X->assign('error_message',$p->reason_declined);
            $X->assign('error','1');
            $fail = 1;

			$Q="SELECT error_template FROM category WHERE category_id='$cat_id'";
			list($template) = mysql_fetch_row(mysql_query($Q));
			$template = "db:".$template;

			//...... Sets the new date for next payment due
			//...... Will try billing tomorrow, up to $config['invoice']['pay_fail'] times.
			$tomorrow = date('Y-m-d',time() + 86400);

			foreach($uniq_id as $uid)
			{
				$Q="UPDATE orders 
					SET payfail=payfail+1,status='due',payment_due='$tomorrow' 
					WHERE uniq_id='$uid'";
				mysql_query($Q);
				print mysql_error();
			}

			/*************************************************/
			/* Loads up the plugin Billing Failure			 */
			/*************************************************/
			includePlugin('','actions','billing_failure',$X);
			/*************************************************/

			if (strlen($template))
			{

				if (!is_on($config['demo']['mode']))
				{
					$msg = stripslashes($X->fetch("$template"));
					print "-> Sending failure email, and a copy to {$config['invoice']['friendly']}: ";
					mail("$email","Billing Failure",$msg,"From: ".$config['invoice']['friendly']." <".$config['invoice']['email'].">\nContent-Type: text/html");
					mail($config['invoice']['email'],"Billing Error",$msg,"From: $email <$fname $lname>\nContent-Type: text/html");
				}
				print "-> Failed payment error email sent.\n\n";
			}
		}
		else
		//************************************
		//*        Purchase Success          *
		//************************************
		{
			$totals += $amount;
			//...... Set paid status
			
			foreach($uniq_id as $uid)
			{
				$Q="SELECT order_id,recurring,quantity,amount,user_id
					FROM orders 
					WHERE uniq_id='$uid' 
					AND payment_due=NOW() 
					AND recurring > 0";

				$res = mysql_query($Q);
				//...... Doing this is not keeping them in the same order group.
				//...... Update the order payment_due for each in the uniq_id group
				$order = new OrderClass($orderData['user']['id'],$uid,$config);

				while($item = mysql_fetch_assoc($res))
				{
					$item['status'] = 'paid';
					$order->updateItem($item);
				}
				$order->postOrder();

				//...... Display the succesful billing template for this category
				$Q="SELECT success_template FROM category WHERE category_id='$cat_id'";
				list($template) = mysql_fetch_row(mysql_query($Q));
				//$template = "db:".$template;

				print "- Loading Plugins for {$item['title']}\n";
				/*************************************************/
				/* Loads up the plugin Billing Success			 */
				/*************************************************/
				includePlugin('','actions','billing_success',$X);
				/*************************************************/

				if (strlen($template))
				{
					$msg = stripslashes($X->fetch("$template"));
					print $msg;
					print "-> Sending success email, and a copy to {$config['invoice']['friendly']}: ";
					mail("$email","Billing Receipt",$msg,"From: ".$config['invoice']['friendly']." <".$config['invoice']['email'].">\nContent-Type: text/html");
					mail($config['invoice']['email'],"Billing Receipt",$msg,"From: $email <$fname $lname>\nContent-Type: text/html");
					print "-> Success receipt email sent.\n\n";
				}
				unset($order);
			}
		}
		print "-------------------------------------\n";
		unset($p);
		$amount = 0;
	}
	print "\n*************************************\n";
	print "Total billing receipt: ".number_format($totals,2)."\n";
	print "Total billing fail   : ".number_format($failTotal,2)."\n";
	print "*************************************\n";
}
else
{
	print "* Nobody to send billing to today.\n";
}

?>
