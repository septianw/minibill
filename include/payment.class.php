<?php
/**
* @desc Merchant gateway abstraction 
* @author Matthew Frederico
* @copyright 2004-2005 MiniBill under the COPv1 license
*/
class Payment
{
	/**
	* @desc Raw return data from Merchant Gateway
	*/
	var $raw_data		= '';

	/**
	* @desc Contains the auth code from processor
	*/
	var $authorization_code	= '';

	/**
	* @desc Contains the plain text response code from the merchant
	*/
	var $response_code		= '';

	/**
	* @desc Contains the plain text "Approved" or "Declined", guilty until innocent.
	*/
	var $response			= "Declined";

	/**
	* @desc Contains the plain text reason why the transaction failed.
	*/
	var $reason_declined	= '';

	var $dbg_level			= 0;

	/**
	* @desc if this transaction is a recurring billing type
	*/
	var $is_recurring		= 0; // 0 or 1

	/**
	* @return Object
	* @param   string  $processor_id Your Processor Id Code (From gateway processor account)
	* @param   string  $merchant_id Your merchant Id Code  (From bank institution)
	* @param   string  $merchant_password Your merchant account password
	* @param   string  $process_url url to perform processing, if necessary.
	* @param   string  [$test_mode] Optional no transaction will be performed, can be set to "TRUE" | "FALSE" (0 | 1)
	* @desc Initialize Merchant Processing
	*/
	function Payment($processor_id,$merchant_id,$merchant_password,$test_mode=0)
	{
		//.. Test mode
		$this->processor_id = $processor_id;
		$this->merchant_id = $merchant_id;
		$this->merchant_password = $merchant_password;
		$this->test_mode		= (intval($test_mode) > 0) ? 1 : 0;
	}

	function set_process_url($process_url)
	{
		$this->process_url = $process_url;
	}
	
	//.. Possibly use these two for paypal
	function set_accept_url($acc_url_value)
	{
		$this->acc_url = $acc_url_value;
	}

	function set_decline_url($dec_url_value)
	{
		$this->dec_url = $dec_url_value;
	}

	function set_referrer($referrer)
	{
		$this->referrer = $referrer;
	}

	//.. Public Functions
	/**
	* @return Void
	* @desc Sets recurring billing flag so as to avoid checking cvv
	*/
	function set_recurring($recur_flag)
	{
		$this->is_recurring = $recur_flag;
	}

	/**
	* @return Void
	* @desc Sends actual payment into processor
	*/
	function send_payment($capture=0)
	{
		require_once("processors/".strtolower($this->processor_id).".php");
	}

	/**
	* @return String "Approved" "Declined"
	* @desc Returns weather or not the payment was approved or declined - see ($this->reason_declined)
	*/
	function get_response()
	{
		return ($this->response);
	}

	/**
	* @return String Response Code
	* @desc Returns the reponse code given from the merchant.
	*/
	function get_response_code()
	{
		return ($this->response_code);
	}

	/**
	* @return Void
	* @desc Sets the shopper Identifier (BOA Specific)
	*/
	function set_shopper_id($shopper_id)
	{
		$this->shopper_id = $shopper_id;
	}

	/**
	* @param   string  $id Unique Order ID
	* @param   string  $amount Total amount this order was for
	* @return Void
	* @desc Sets up the order id and amount information for a transaction
	*/
	function set_order($customer_id,$order_id,$amount,$description='')
	{
		$this->customer_id		= $customer_id;
		$this->order_id			= $order_id;
		$this->order_amount		= $amount;
		$this->description		= $description;
	}

	/**
	* @param   string  $cc_name Name on issued credit card
	* @param   string  $cc_number Credit card number
	* @param   string  $cc_expire Date card will expire in the format: mm/dd/yyyy. Use 01 if day not available.
	* @param   string  $cc_verify_value 3 digit card verification number (on back of card)
	* @return Void
	* @desc Sets up the order id and amount information for a transaction
	*/
	function set_card_info($cc_name,$cc_number,$cc_expire,$cc_verify_value)
	{
		$this->cc_name			= $cc_name;
		$this->cc_number		= $cc_number;
		$this->cc_expire_date	= $cc_expire;
		$this->cc_verify_value	= $cc_verify_value;
		list($this->cc_expire_month,$this->cc_expire_year) = split("\/",$cc_expire);
	}

	/**
	* @param   string  $firstname First name of card holder
	* @param   string  $lastname Last name of card holder
	* @param   string  $email Email address of card holder
	* @param   string  $address1 First line address of card holder
	* @param   string  $address2 Second line address of card holder
	* @param   string  $city City of card holder
	* @param   string  $state State of card holder
	* @param   string  $zip Zipcode of card holder
	* @param   string  $country Country Code of card holder
	* @param   string  $phone phone number of card holder
	* @return Void
	* @desc Sets up the card holder billing information
	*/
	function set_billing_info($firstname,$lastname,$email,$address1,$address2,$city,$state,$zip,$country,$phone)
	{
		$this->billing_email		= $email;
		$this->billing_firstname	= $firstname;
		$this->billing_lastname		= $lastname;
		$this->billing_address1		= $address1;
		$this->billing_address2		= $address2;
		$this->billing_city			= $city;
		$this->billing_state		= $state;
		$this->billing_zip			= $zip;
		$this->billing_country		= $country;
		$this->billing_phone		= $phone;
	}
}

/*
//.. PGAL
//require_once("payment.class.php");

//.. Instantiate object: service id, merchant_id, merchant_password,test_mode
$p = new Payment('AUTHNET','','',1);

//.. 
$p->dbg_level=1;

//.. Set up the order: customer_id,order_id,amount
$p->set_order('234234324234','234234234','9.35');

//.. Order card info: Name On Card, CC Number, Exp Date, Verify Code
$p->set_card_info('Billy Testeroni','5424000000000015','02/2009','234');

//.. Order Billing Info: firstname, lastname, email, address1, address2, city, state, zip, country, phone
$p->set_billing_info('Billy','Testeroni','billy@testeroni.com','527 Strong Road','','Cornwallville','NY','12418','US','5185551234');

//.. Send the payment information to gateway
$p->send_payment();

//.. Grab the response
$valid = $p->get_response();

//.. If Invalid, tell us why
if (!$valid) 
{
	print "Declined: ".$p->reason_declined;
	print_r($p);
}
else
{
   print "Approved";
}
*/

?>
