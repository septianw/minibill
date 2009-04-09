<?php
/*
txn_type: subscr_payment
payment_date: 03:37:10 Aug 19, 2005 PDT
subscr_id: S-30J58445JP707963A
last_name: Mills
item_name: Karl Mills - Hosting
payment_gross: 19.95
mc_currency: USD
business: mfrederico@gmail.com
payment_type: instant
verify_sign: AXXBWZRGco3F2ZMi2iefPL2kNKuRA4i4hW4MrejSPWjQbwQmGH00F2vu
payer_status: verified
payer_email: kmills@web-design-system.com
txn_id: 9CV13256N2011320R
receiver_email: mfrederico@gmail.com
first_name: Karl
payer_id: 5GK67N78L2QQJ
receiver_id: KJXEJPDUQY9L8
item_number: HOST-19
payment_status: Completed
payment_fee: 1.08
mc_fee: 1.08
mc_gross: 19.95
charset: windows-1252
notify_version: 1.9

txn_type: subscr_payment
payment_date: 12:38:59 Jan 10, 2006 PST
subscr_id: S-3SB124031U963251J
last_name: Watanobe
item_name: Order Description
payment_gross: 9.35
mc_currency: USD
business: matt@ultrize.com
payment_type: instant
verify_sign: ASdj65HR9B38ygL-kCfHZUNmjVy.AWXfzUrYP0z6dXgHeq1Tpu67900J
payer_status: verified
test_ipn: 1
payer_email: billy@ultrize.com
txn_id: 02K48799WW797701C
receiver_email: matt@ultrize.com
first_name: Billy
invoice: 234234234
payer_id: AG74R8A2LQT66
receiver_id: DJCB5QM3E6X5U
payment_status: Completed
payment_fee: 0.57
mc_fee: 0.57
mc_gross: 9.35
charset: windows-1252
notify_version: 2.0

txn_type: subscr_signup
subscr_id: S-3SB124031U963251J
last_name: Watanobe
item_name: Order Description
mc_currency: USD
amount3: 9.35
recurring: 0
verify_sign: A1agNYoCRzYU46so8rLFlabHjbAfAliFpXWBsUAaHnMUJbfYxR6OAH5u
payer_status: verified
test_ipn: 1
payer_email: billy@ultrize.com
first_name: Billy
receiver_email: matt@ultrize.com
payer_id: AG74R8A2LQT66
invoice: 234234234
reattempt: 0
subscr_date: 12:38:58 Jan 10, 2006 PST
charset: windows-1252
notify_version: 2.0
period3: 1 M
mc_amount3: 9.35
*/

// http://securitytracker.com/alerts/2006/Aug/1016769.html
/* FIX */

if (!defined('LOADED'))
{
    print("<h1>Possible hacking attempt</h1>");
    print("<h3>This has been recorded and logged, have a nice day!</h3>");
    error_log("Breakin Attempt detected in: ".__FILE__." from {$_SERVER['REMOTE_ADDR']}");
    exit();
}
/* FIX */

include($config['include_dir']."paypal_ipn.class.php");
$p = new paypal_class;
if ($config['merchant']['test'])
{
    //...... Sandbox
    $paypal_url = "https://www.sandbox.paypal.com/us/cgi-bin/webscr";
}
else
    $paypal_url = $config['merchant']['paypal_url'];

$p->ipn_log_file = $config['merchant']['paypal_ipn_log'];
$p->paypal_url = $paypal_url;

if ($p->validate_ipn())
{
	if ($p->ipn_data['payment_status'] == 'Completed')
	{
		//...... Get the customer ID from the invoice ID
		list($customer_id,$uniq_id) = explode(".",$_POST['invoice']);

		$_SESSION['id']		= $customer_id;

		$Q="SELECT 
				*
			FROM 
				users 
			WHERE 
				id='{$_SESSION['id']}' 
			LIMIT 1";

		$_REQUEST['user'] = mysql_fetch_assoc(mysql_query($Q));
		$_SESSION['email']	= $_REQUEST['user']['email'];

		//...... include/order.class.php
		$o = new OrderClass($customer_id,'',$config,'PAYPAL');

		$i=1;
		while(isset($_POST['item_number'.$i]))
		{
			$_SESSION['prods'][$i]['id'] = $_POST['item_number'.$i];
			$_SESSION['prods'][$i]['quantity'] = $_POST['quantity'.$i];
			$i++;
		}

		foreach($_SESSION['prods'] as $prod) 
		{
			$o->addItem($prod['id'],$prod['quantity']);
		}

		$o->postOrder();

		// Yes, its supposed to be the uniq_id
		// Just one of those strange naming what-to-do's

		/*************************************************/
		/* Loads up the plugin Billing Success           */
		/*************************************************/
		includePlugin('','actions','success_purchase',$X);
		/*************************************************/
	}
	else
	{
		/*************************************************/
		/* Loads up the plugin Billing Failure           */
		/*************************************************/
		includePlugin('','actions','fail_purchase',$X);
		/*************************************************/
	}

}
else
{
	$redirect_to=($config['secure']['url']);
}
?>
