<?php
$dbg = 0;

session_write_close();
//.. MiniBill paypal IPN Plugin 

global $config;
$this->process_url = $config['paypal']['notify_url'];

if ($this->test_mode)  
{
	//...... Sandbox
	$paypal_url = "https://www.sandbox.paypal.com/us/cgi-bin/webscr";
}
else
	$paypal_url = $config['merchant']['paypal_url'];

//...... Ipn class .. Thank goodness someone wrote this.. what a monster!
include("include/paypal_ipn.class.php");

$prods = $_SESSION['prods'];

if ($dbg) print "TERM: ".substr($prods[0]['is_recurring'],0,1);

$p = new paypal_class;
$p->paypal_url = $paypal_url;

//...... Check here if the product is a 
//...... subscription product or a 1 time.
if ($prods[0]['is_recurring'] == 'yes')
{
	$p->add_field('cmd',			'_xclick-subscriptions');
	$p->add_field('a3',				$this->order_amount);
	$p->add_field('t3', 			substr($prods[0]['is_recurring'],0,1));
	$p->add_field('p3', 			'1');
	$p->add_field('no_note', 		'1');
}
else
{
	$p->add_field('cmd',			'_cart');
	$p->add_field('upload',			1); // Indicates use of 3rd party shopping cart.
}

$i = 1;

foreach($prods as $idx=>$prod)
{
	$append = "_".$i++;

	if ($dbg) print_pre($prod);
	if ($dbg) print $append;

	$p->add_field('item_name'.$append,$prod['title']);
	$p->add_field('item_number'.$append,$prod['id']);
	$p->add_field('amount'.$append,$prod['price']);
	$p->add_field('quantity'.$append,$prod['quantity']);
}

$p->add_field('business',	$this->merchant_id);
$p->add_field('notify_url',	$this->process_url);
$p->add_field('user_id',	$this->customer_id);
$p->add_field('invoice',	intval($this->customer_id).".$this->order_id");
$p->add_field('first_name', $this->billing_firstname);
$p->add_field('last_name',	$this->billing_lastname);
//$p->add_field('email',		$this->billing_email);
$p->add_field('email',		$_REQUEST['paypal_id']);
$p->add_field('city',		$this->billing_city);
$p->add_field('state',		$this->billing_state);
$p->add_field('zip',		$this->billing_zip);

//...... If any configuration "paypal" 
//...... variables are set, send these as well.
if (is_array($config['paypal']))
{
	foreach($config['paypal'] as $key=>$value) $p->add_field($key,		$value);
}
	
if ($dbg)
{
	print_pre($p);
	print_pre($_SESSION);
	exit();
}

$p->submit_paypal_post();

//...... Handing everything off to PayPal 
//...... Just terminate here
exit();

?>
