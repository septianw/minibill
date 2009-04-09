<?php
//.. MiniBill Authorize.net Plugin 

//...... Developer URL Processing
$this->process_url = "https://test.authorize.net/gateway/transact.dll";

//...... Live Processing
$this->process_url = "https://secure.authorize.net/gateway/transact.dll";

//...... Authorize.net credit card verification
$this->postvars[x_login] 		= $this->merchant_id;
if ($this->test_mode)  $this->postvars[x_test_request] = 'true';
$this->postvars[x_tran_key] 	= $this->merchant_password;
$this->postvars[x_type]		= "AUTH_CAPTURE";
$this->postvars[x_version]	= "3.1";
$this->postvars[x_delim_data]	= "true";
$this->postvars[x_relay_response]	= "false";


//...... Set up customer order fields
$this->postvars[x_cust_id]    = $this->customer_id;
$this->postvars[x_invoice_num]= $this->order_id;
$this->postvars[x_description]= $this->comment_field;
$this->postvars[x_email]      = $this->billing_email;
$this->postvars[x_first_name] = $this->billing_firstname;
$this->postvars[x_last_name]  = $this->billing_lastname;
$this->postvars[x_address]    = $this->billing_address1;
$this->postvars[x_city]       = $this->billing_city;
$this->postvars[x_state]      = $this->billing_state;
$this->postvars[x_zip]        = $this->billing_zip;
$this->postvars[x_phone]      = $this->billing_phone;
$this->postvars[x_country]    = $this->billing_country;
$this->postvars[x_comments]   = $this->comment_field;
$this->postvars[x_card_code]  = $this->cc_verify_value;
$this->postvars[x_exp_date]   = $this->cc_expire_date;
$this->postvars[x_company]	  = $this->billing_firstname.' '.$this->billing_lastname;

//...... Don't need the CVV Code
if (!$this->is_recurring) $this->postvars[x_card_num]   = $this->cc_number;
$this->postvars[x_amount]     = $this->order_amount;

//....... Create a string authnet likes
foreach ($this->postvars as $key=>$value)
{
	$this->postfields .= "$key=".urlencode($value)."&";
}


//.. Post info to Authorize.net
$this->C = curl_init($this->process_url);
//curl_setopt($this->C, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($this->C, CURLOPT_TIMEOUT,10);
curl_setopt($this->C, CURLOPT_POST,1);
curl_setopt($this->C, CURLOPT_REFERER,$this->referrer);
curl_setopt($this->C, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($this->C, CURLOPT_HEADER, 0);
curl_setopt($this->C, CURLOPT_POSTFIELDS,$this->postfields);
$this->raw_response = curl_exec($this->C);
curl_close($this->C);
// Send result to the appropriate page for referrer
$result_array = preg_split("/<br>/i",$this->raw_response,-1, PREG_SPLIT_NO_EMPTY);

//...... Handle the raw result
list($authNet['responseCode'],
	$authNet['subCode'],
	$authNet['reasonCode'],
	$authNet['reasonText'],
	$authNet['authCode'],
	$authNet['avsResult'],
	$authNet['txId'],
	$authNet['orderId']) = split(",",$this->raw_response);

//...... Set public varaibles for this attempt
$this->reason_code			= $authNet['reasonCode'];
$this->response_code		= $authNet['responseCode'];
$this->response				= ($authNet['responseCode'] == 1) ? 1 : 0;
if (!$this->response)		$this->reason_declined = $authNet['reasonText'];

$this->invalid_fields		= $IOC_invalid_fields;
$this->authorization_code	= $authNet['authCode'];
$this->order_id				= $authNet['txId'];
$this->merchant_order_id	= $authNet['orderId'];
$this->AVS_result			= $authNet['avsResult'];

?>
