<?php
//.. Bank of America Merchant Processing a PGAL plugin
$this->postfields="IOC_merchant_id=".$this->merchant_id."&";
if ($this->shopper_id) $this->postfields.="IOC_merchant_shopper_id=".$this->shopper_id."&";
$this->postfields.="IOC_merchant_order_id=".$this->order_id."&";
$this->postfields.="IOC_order_total_amount=".$this->order_amount."&";
$this->postfields.="Ecom_billto_postal_name_first=".$this->billing_firstname."&";
$this->postfields.="Ecom_billto_postal_name_last=".$this->billing_lastname."&";
$this->postfields.="Ecom_billto_postal_street_line1=".$this->billing_address1."&";
$this->postfields.="Ecom_billto_postal_street_line2=".$this->billing_address2."&";
$this->postfields.="Ecom_billto_postal_city=".$this->billing_city."&";
$this->postfields.="Ecom_billto_postal_stateprov=".$this->billing_state."&";
$this->postfields.="Ecom_billto_postal_postalcode=".$this->billing_zip."&";
$this->postfields.="Ecom_billto_postal_countrycode=".$this->billing_country."&";
$this->postfields.="Ecom_billto_online_email=".$this->billing_email."&";
$this->postfields.="Ecom_payment_card_name=".$this->cc_name."&";
$this->postfields.="Ecom_payment_card_number=".$this->cc_number."&";

//...... For recurring billing, we don't keep the 3 digit cc verify card in hand
if ($this->is_recurring)
{
	print "* Recurring\n";
	$this->postfields.="ioc_CVV_Indicator=0&";
}
else
{
	$this->postfields.="IOC_CVV_Indicator=".$this->BOA_CVV_indicator."&";
	$this->postfields.="Ecom_Payment_Card_Verification=".$this->cc_verify_value."&";
}
$this->postfields.="Ecom_payment_card_expdate_month=".$this->cc_expire_month."&";
$this->postfields.="Ecom_payment_card_expdate_year=".$this->cc_expire_year."&";

//.. post info to Bank of America
if(!$this->test_mode)
{
	$this->C = curl_init("https://cart.bamart.com/payment.mart");
	//$this->C = curl_init($this->process_url);
	curl_setopt($this->C, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($this->C, CURLOPT_TIMEOUT,60);
	curl_setopt($this->C, CURLOPT_POST,1);
	curl_setopt($this->C, CURLOPT_REFERER,$this->referrer);
	curl_setopt($this->C, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($this->C, CURLOPT_HEADER, 0);
	curl_setopt($this->C, CURLOPT_POSTFIELDS,$this->postfields);
	$this->response = curl_exec($this->C);
	curl_close($this->C);
	// Send result to the appropriate page for referrer
	$result_array = preg_split("/<br>/i",$this->response,-1, PREG_SPLIT_NO_EMPTY);
	$this->raw_data				= $result_array;

	//...... IS this necessary?
	$this->postfields  = "ip_address=".$_SERVER['REMOTE_ADDR']."&";
	$this->postfields .= "remote_host=".urlencode($_SERVER['REMOTE_HOST'])."&";

	$this->dbg_response['raw'] = $this->raw_data;
	//...... Handle the results
	for ($i = 0; $i < sizeof($result_array); $i++)  
	{
		list($key,$value) = preg_split("/=/",$result_array[$i]);
		$this->dbg_response['results'][$key] = $value;
		$$key = $value;
	}

	//...... Set public varaibles for this response
	$this->response_code		= $IOC_response_code;
	$this->response				= ($IOC_response_code==0) ? 1 : 0;
	if (!$this->response)		$this->reason_declined = $IOC_reject_description;
	$this->invalid_fields		= $IOC_invalid_fields;
	$this->authorization_code	= $IOC_authorization_code;
	$this->order_id				= $IOC_order_id;
	$this->merchant_order_id	= $IOC_merchant_order_id;
	$this->shopper_id       	= $IOC_shopper_id;
    $this->complete             = $Ecom_transaction_complete;
	$this->AVS_result			= $IOC_AVS_result;
	if (isset($IOC_invalid_fields))
	{
		$this->invalid_fields = ltrim(rtrim($IOC_invalid_fields,")"),"(");
		$this->invalid_fields = str_replace('Ecom_','',$this->invalid_fields);
	}
/*
       0: m=secure01
       1: IOC_merchant_order_id=16738842
       2: IOC_merchant_shopper_id=
       3: IOC_shopper_id=1C69QDGKE4RH8GTRTVSS888TE78R98G5
       4: IOC_response_code=0
       5: Ecom_transaction_complete=TRUE
       6: IOC_pcard_response=N
       7: Ecom_Payment_Card_Verification_RC=P
       8: IOC_AVS_result=4
       9: IOC_order_total_amount=24
       10: IOC_order_id=555951
       11: IOC_authorization_amount=24
       12: IOC_authorization_code=030365
*/

}

if ($this->dbg_level > 0)
{
	$fields = split("\&",$this->postfields);
	$this->dbg_response['post']['process_url'] = $this->process_url;
	foreach($fields as $vals)
	{
		list($key,$val) = split("=",$vals);
		$this->dbg_response['post'][$key] = $val;
	}
}
?>
