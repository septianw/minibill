<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Licensed under GPL                          */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/
//**********************************************
//* If they "refresh" their browser after      *
//* a purchase don't charge a duplicate order. *
//**********************************************


global $thisAction;
global $thisPage;
global $sysMsg;
global $config;
global $DEMO_MODE;

if ($_SESSION['order_id'])
{
	unset($_SESSION['prods']);
	unset($_SESSION['user']);
	unset($_SESSION['grand_total']);

	session_write_close();
	header("Location: index.php?page=history");
	exit();
}

if($_REQUEST['login'])
{
	$Q="SELECT 
			id 
		FROM 
			users 
		WHERE email='".strtolower(addslashes($_POST['user']['email']))."' 
			AND password='".addslashes($_POST['user']['password'])."' 
		LIMIT 1";
	list($_POST['user']['id']) = mysql_fetch_row(mysql_query($Q));
}

//...... Determines if a credit card is required
$payment_gateway = strtoupper(basename($_REQUEST['payment_type']));
$card_required   = !preg_match("/^(?:PAYPAL)/",$payment_gateway);

//...... This is the most retarded thing I have ever seen.
//...... Explain what is happening here!
//...... We have a user id in the form (pre-pop)
if (isset($_POST['user']['id']) && isset($_POST['uniq_id']))
{
	$Q="SELECT email,password 
		FROM users,orders 
		WHERE id='{$_POST['user']['id']}' 
		AND orders.user_id='{$_POST['user']['id']}' 
		AND uniq_id='{$_POST['uniq_id']}' LIMIT 1";

	list($email,$pass) = mysql_fetch_row(mysql_query($Q));
	
	$_POST['user']['email']		= $email;
	$_POST['user']['password']  = $pass;

	//..... Should we pull the credit card on file as well - SCARY!
}

//*****************************************************
//* They have made an order .. and have a grand_total *
//*****************************************************
if (isset($_SESSION['grand_total']))
{
	/*************************************************/
	/* Loads up the plugin PRE modules for this PAGE */
	/*************************************************/
	includePlugin('pre','actions',$thisAction,$X);
	/*************************************************/

	$X->assign('amount',$_SESSION['grand_total']);
	$X->assign('grand_total',number_format($_SESSION['grand_total'],2));
	$X->assign('total_quantity',number_format($total_quantity));

	if ($card_required)
	{
		$fail = has_values(array(
		'cc_exp_yr',
		'cc_exp_mo',
		'cardnum',
		'cvv'),$X);

		//...... PRE-validate expiration
		$expired_date = date("Ym");
		$card_date	  = $_POST['user']['cc_exp_yr'].$_POST['user']['cc_exp_mo'];
		if ($card_date < $expired_date)
		{
			$X->assign('error_cc_exp_yr','error');
			$X->assign('error_cc_exp_mo','error');
			$fail=1;
		}
		if (!credit_card::validate ($_POST['user']['cardnum'])) 
		{
			$X->assign('error_cardnum','error');
			$fail=1;
		}
	}

	//...... Verify posted information is correct
	if (isset($_POST['user']))
	{
		//...... Validate Email address
		if (!validate_email($_POST['user']['email']))
		{
			$X->assign('error_email','error');
			$fail=1;
		}

		$Q="SELECT * FROM users 
			WHERE email='".addslashes($_POST['user']['email'])."' 
			LIMIT 1";

		$info = mysql_fetch_assoc(mysql_query($Q));
		$X->assign('config',$config);

		if (!is_array($info))
		{
			//...... We have a new user?
			$X->assign('newUser',1);
			$X->assign('email',$_POST['user']['email']);
			$X->assign('password',$_POST['user']['password']);

            if (!$_POST['user']['phone']) $X->assign('error_phone','error');
            if (!$_POST['user']['lastname']) $X->assign('error_lastname','error');
            if (!$_POST['user']['firstname']) $X->assign('error_firstname','error');
            if (!$_POST['user']['address']) $X->assign('error_address','error');
            if (!$_POST['user']['city']) $X->assign('error_city','error');
            if (!$_POST['user']['state']) $X->assign('error_state','error');
            if (!$_POST['user']['zipcode']) $X->assign('error_zipcode','error');
			if (!$_POST['user']['country']) $X->assign('error_country','error');

			foreach($_POST['user'] as $key=>$val) 
			{
				if (!preg_match('/cardnum|cc_exp_yr|cc_exp_mo/',$key)) 
				{
					$X->assign($key,$val);
				}
			}
		}
		//...... Fill in the billing details
		elseif (($info['password'] == $_POST['user']['password']) && ($info['id'] > 0))
		{
			foreach($info as $key=>$val) 
			{
				if (!preg_match('/cardnum|cc_exp_yr|cc_exp_mo/',$key)) $X->assign($key,$val);
			}
			$X->assign('valid_password','1');
		}
		else
		{
			$fail = 1;
			$X->assign('error_email','error');
			$X->assign('error_password','error');
		}

		if (!$_REQUEST['login'])
		{
			//: Check to make sure we have even have values
			$fail += has_values(array(
			'email',
			'password',
			'firstname',
			'lastname',
			'address',
			'city',
			'phone',
			'state',
			'zipcode',
			'country'),$X);
		}
	}
	//...... Fail because we don't have any user information posted
	else
	{
		$fail = 1;
	}

	//...... if we don't fail on card expired, or email invalid, and passwords match
	if (!$fail && !$_REQUEST['login'])
	{
		$cvv		= $_POST['user']['cvv'];
		unset($_POST['user']['cvv']);

		//...... Generate the query set with values
		foreach($_POST['user'] as $key=>$val) 
		{
			if ($key == 'cardnum') $val = data_encrypt($val,$config['secret_key']);
			$set .= "\n$key='".addslashes($val)."',";
		}
		$set[strlen($set) -1] = ' ';
		
		$amount = $_SESSION['grand_total'];

		/***************************/
		/* Create or update a user */
		/***************************/

		//...... the "$set" variable is created from the form ($_POST['user'])
		if ($info['id']) $set.= " WHERE id='$info[id]'";
		
		//...... Init Query to update or insert the user's data
		$Q	= ($info['id'] > 0) ? "UPDATE" : "INSERT INTO";

		//...... if we have a new user, set new user stamp
		if (!$info['id']) $set .= ",user_stamp=NOW()";

		//...... Query construct
		$Q .= " users SET $set";
		mysql_query($Q);

		if (!$info['id']) $info['id'] = mysql_insert_id();

		//**********************/
		//* Create a new order */
		//**********************/

		//...... include/order.class.php
		$o = new OrderClass($info['id'],'',$config,$payment_gateway);

		//********************************************************************
		//* Talk to the merchant gateway                                     *
		//********************************************************************
		require_once($config[include_dir]."payment.class.php");

		$id		= strtolower($payment_gateway).'_id';
		$pass	= strtolower($payment_gateway).'_pass';

		//.. Instantiate object: service id, merchant_id, merchant_password, test_mode
		$p = new Payment($payment_gateway,$config['merchant'][$id],$config['merchant'][$pass],$config['merchant']['test']);

		$desc = $config['company']['name'];
		//...... Turn on debug mode? 
		//$p->dbg_level=0;

		//.. Set the http referrer
		$p->set_referrer($config['merchant']['referer']);

		//.. Set up the order: customer_id,order_id,amount
		$p->set_order($info['id'],$o->uniq_id,$amount,$desc);

		//...... Cardnum here is still decryped from the form
		//.. Order card info: Name On Card, CC Number, Exp Date, CVV 
		$p->set_card_info(	$_POST['user']['firstname'].' '.$_POST['user']['lastname'],
							$_POST['user']['cardnum'],
							$_POST['user']['cc_exp_mo'].'/'.$_POST['user']['cc_exp_yr'],
							$cvv);

		//.. Order Billing Info: firstname, lastname, email, address1, address2, city, state, zip, country,phone
		$p->set_billing_info(	$_POST['user']['firstname'],
								$_POST['user']['lastname'],
								$_POST['user']['email'],
								$_POST['user']['address'],'',
								$_POST['user']['city'],
								$_POST['user']['state'],
								$_POST['user']['zipcode'],
								$_POST['user']['country'],
								$_POST['user']['phone']);


		//.. Send the payment information to gateway
		$p->send_payment();

		//.. Grab the response
		$valid = $p->response; // Returns 1 (approved) or 0 (declined)

		//...... Check for demo mode or test mode, and make it ALWAYS valid
		if ($DEMO_MODE || $config['merchant']['test']) $valid = 1;

//************************************
//*        Purchase Failure          *
//************************************
		if (!$valid)
		{
			//...... Handle authorize.net error messages 
			//...... But I need it to handle more than Just AuthNet messages ... 
			$X->assign('error_message',$p->reason_declined);
			if ($p->reason_code == 6 || $p->reason_code == 37)
			{
				$X->assign('error_cardnum','error');
			}
			if ($p->reason_code == 12)
			{
				$X->assign('error_cvv','error');
			}
			if ($p->reason_code == 7 || $p->reason_code == 8) 
			{ 
				$X->assign('error_cc_exp_mo','error');
				$X->assign('error_cc_exp_yr','error');
			}
			if ($p->reason_code == 27)
			{
				$X->assign('error_address','error');
				$X->assign('error_city','error');
				$X->assign('error_state','error');
				$X->assign('error_zipcode','error');
				$X->assign('error_country','error');
			}

			$X->assign('cardError','1');
			$X->assign('prods',$_SESSION['prods']);

			$fail = 1;
			/*******************************************/
			/* Loads up the plugin FAILURE modules for */
			/*******************************************/
			includePlugin('fail','actions',$thisAction,$X);
			/*******************************/
			//...... This is where the actual login stuff happens.
			//$info['cardnum'] = data_decrypt($info['cardnum'],$config['secret_key']);

			$sysMsg->addMessage("There is an error processing this credit card!<br /><b>$p->reason_declined</b>",'#FFFFFF','#A00000');
			//..... Turns this action into a page
			unset($thisAction);
			$thisPage = 'orderform';
			$X->assign('thisPage',$thisPage);
		}
//************************************
//*       Purchase successful        *
//************************************
		else
		{
			//...... Auto login user after purchase
			$_SESSION['id']			= $info['id'];
			$_SESSION['email']		= $_POST['user']['email'];
			$_SESSION['password']	= $_POST['user']['password'];


			//...... Sets the old item to paid and not to recur, 
			//...... creates new entry
			if (isset($_REQUEST['order_id'])) 
			{
				$item['order_id'] = $_REQUEST['order_id'];
				$item['status'] = 'paid';

				$o->updateItem($item);
			}
			else
			{
				if ($_SESSION['prods'])
					foreach($_SESSION['prods'] as $prod)
						$o->addItem($prod['id'],$prod['quantity'],$prod['amount']);
			}

			$o->postOrder();

			// Yes, its supposed to be the uniq_id
			// Just one of those strange naming what-to-do's
			$_SESSION['order_id']	= $o->uniq_id; 

			/*******************************************/
			/* Loads up the plugin SUCCESS modules for */
			/*******************************************/
			includePlugin('success','actions',$thisAction,$X);
			/*******************************************/
			$redirect_to = "index.php?page=thankyou&nomenu=1";
		}
	}
	else
	{
		//...... This is where the actual login stuff happens.
		//$info['cardnum'] = data_decrypt($info['cardnum'],$config['secret_key']);

		$_SESSION['user'] = $info;
		if (!$_REQUEST['login'])
		{
			$sysMsg->addMessage("There is an error in this form!<br />Please see hilighted items for details.",'#FFFFFF','#A00000');
		}
		else $X->assign('login');

		//..... Turns this action into a page
		unset($thisAction);
		$thisPage = 'orderform';
	}
}
//...... You really,really shouldn't ever get here...
else
{
	//...... Should prolly redirect to the shopping cart here.
	$X->assign('error','1');
	$X->assign('title',"Internal Error - my fat cat sat on a hat and spat on a bat (no grand total!)");
	$thisPage = "error";
}

if ($fail) $X->assign('error',$fail);

//...... Checks to make sure values are set
function has_values($array,&$smarty)
{
	$fail = 0;
	foreach($array as $key)
	{
		$smarty->assign($key,$_POST['user'][$key]);
		if (!strlen($_POST['user'][$key]))
		{
			$smarty->assign("error_$key","error");
			$fail = 1;
		}
	}
	return($fail);
}
?>
