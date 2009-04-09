<?php
//dl('php4_cybersource.so');
$config['merchantID'] = $this->merchant_id;

$config['keysDirectory']="/home/cart.coffeecup.com/includes/cybersource-keys/";
$config['targetAPIVersion']="1.10";
$config['sendToProduction']= ($this->test_mode == 0) ? "true" : "false";
$config['enableLog']="true";
$config['logDirectory']="/tmp";

if ($this->dbg_level > 1) print "<pre>";
$results = runAuth($config, $this);
$this->raw_data = $results;

//...... Cybersource says don't use AVS as much ...
$AVSOK = preg_match("/[DGMPSUXY]/",$results['ccAuthReply_avsCode']);
$CVVOK = preg_match("/[MP]/",$results['ccAuthReply_cvCode']);

//...... Create a response
$this->response				= (($results['decision'] == 'ACCEPT')) ? 'Approved' : 'Declined';
$this->response_code		= $results['reasonCode'];
$this->requestID			= $results['requestID'];
$this->reason_declined		= reasonCode2English($results[reasonCode]);
$this->reject_description	= reasonCode2English($results[reasonCode]);
$this->AVS_result			= $results['ccAuthReply_avsCode'];
$this->authorization_code	= $results['ccAuthReply_authorizationCode'];

//...... This should just happily Capture the funds
if ($capture && $this->response == 'Approved')
{
	runCapture($config,$results,$this);
}

if (!$AVSOK) $this->reason_declined .= "\n<br />".map_cybersource_avs_codes($results['ccAuthReply_avsCode']);
if (!$CVVOK) $this->reason_declined .= "\n<br />".map_cybersource_cvv_codes($results['ccAuthReply_cvCode']);

if ($this->dbg_level > 2)
{
	print "<pre>";
	print_r($results);
	print_r($this);
}

// we will let the CyberSource PHP extension get the merchantID from the
// $config array and insert it into $request.

//-----------------------------------------------------------------------------
function runAuth($config,$this)
//-----------------------------------------------------------------------------
{
	// this is your own tracking number.  CyberSource recommends that you
	// use a unique one for each order.
	$request = array();
    $request['ccAuthService_run'] = "true";
	$request['merchantReferenceCode'] = $this->order_id;

	if (!isset($this->invoice_merchantContact)) $this->invoice_merchantContact = "";
	if (!isset($this->invoice_descriptor))		$this->invoice_descriptor = "Order $this->order_id";

	if ($this->invoice_descriptor && $this->invoice_merchantContact)
	{
		$request['invoiceHeader_merchantDescriptor']		= $this->invoice_descriptor;
		$request['invoiceHeader_merchantDescriptorContact'] = $this->invoice_merchantContact;
	}

	//...... Billing info
	$request['billTo_firstName']	= $this->billing_firstname;
	//...... They HAVE to have a lastname and a firstname
	$request['billTo_lastName']		= (strlen($this->billing_lastname)) ? $this->billing_lastname : $this->billing_firstname;
	$request['billTo_street1']		= $this->billing_address1;
	$request['billTo_city']			= $this->billing_city;
	$request['billTo_state']		= $this->billing_state;
	$request['billTo_postalCode']	= $this->billing_zip;
	$request['billTo_country']		= $this->billing_country;
	$request['billTo_email']		= $this->billing_email;
	$request['billTo_ipAddress']	= $_SERVER['REMOTE_ADDR'];
	$request['billTo_phoneNumber']	= $this->billing_phone;

	//...... Credit Card info
	$request['card_accountNumber']	= $this->cc_number;
	$request['card_expirationMonth']= $this->cc_expire_month;
	$request['card_expirationYear']	= $this->cc_expire_year;
	$request['card_cvIndicator']	= $this->is_recurring;
	$request['card_cvNumber']		= $this->cc_verify_value;

	$request['purchaseTotals_currency'] = 'USD';

	//...... shipping info not necessary ... 
	$request['shipTo_firstName']	= $this->billing_firstname;
	$request['shipTo_lastName']		= $this->billing_lastname;
	$request['shipTo_street1']		= $this->billing_address1;
	$request['shipTo_city']			= $this->billing_city;
	$request['shipTo_state']		= $this->billing_state;
	$request['shipTo_postalCode']	= $this->billing_zip;
	$request['shipTo_country']		= $this->billing_country;
	$request['item_0_unitPrice']	= $this->order_amount;

	// send request now
	$reply = array();
	$status = cybs_run_transaction( $config, $request, $reply );
	if ($this->dbg_level > 1) print_r($status);
	return ($reply);				
}

function runCapture( $config, $results,$this)
{
    // set up the request by creating an array and adding fields to it
    $request = array();
    $request['ccCaptureService_run'] = 'true';
    $request['merchantReferenceCode'] = $this->order_id;

    // reference the requestID returned by the previous auth.
    $request['ccCaptureService_authRequestID'] = $this->requestID;

    $request['purchaseTotals_currency'] = 'USD';
    $request['item_0_unitPrice'] = $this->order_amount;

    $reply = array();
    $status = cybs_run_transaction( $config, $request, $reply );
	if ($this->dbg_level > 1) print_r($status);
	return $reply;
}



function reasonCode2English($id)
{
	$code[100] = "Successful transaction.";
	$code[101] = "The request is missing one or more required fields.";
	$code[102] = " or more fields in the request contains invalid data.";
	$code[104] = "The merchantReferenceCode sent with this authorization request matches the merchantReferenceCode of another authorization request that you sent in the last 15 minutes.";
	$code[150] = "Error: General system failure,  Wait a few minutes and resend the request.";
	$code[151] = "Error: The request was received but there was a server timeout. This error does not include timeouts between the client and the server.";
	$code[152] = "Error: The request was received, but a service did not finish running in time.";
	$code[201] = "The issuing bank has questions about the request. You do not receive an authorization code programmatically, but you might receive one verbally by calling the processor.";
	$code[202] = "Credit card has expired. Please use a different card or other form of payment.";
	$code[203] = "General decline of the card. No other information provided by the issuing bank.  Please use a different card or other form of payment.";
	$code[204] = "Insufficient funds in the account. Please use a different card or other form of payment.";
	$code[205] = "This card is reported as a stolen or lost card.";
	$code[207] = "Issuing bank unavailable. Please Wait a few minutes and resend the request.";
	$code[208] = "Inactive card or card not authorized for card-not-present transactions. Please use a different card or other form of payment.";
	$code[210] = "The card has reached the credit limit. Please use a different card or other form of payment.";
	$code[211] = "Invalid card verification number.  Please use a different card or other form of payment or verify the code.";
	$code[221] = "The customer matched an entry on the processor's negative file.";
	$code[231] = "Invalid account number.  Please use a different card or other form of payment.";
	$code[232] = "The card type is not accepted by the payment processor.  Please use a different card or other form of payment."; 
	$code[233] = "General decline by the processor.  Please use a different card or other form of payment.";
	$code[234] = "There is a problem with your CyberSource merchant configuration.";
	$code[235] = "The requested amount exceeds the originally authorized amount.";
	$code[236] = "Processor failure, the payment processing system is unavailable temporarily, and to try their order again in a few minutes.";
	$code[238] = "The authorization has already been captured.";
	$code[239] = "The requested transaction amount must match the previous transaction amount.";
	$code[240] = "The card type sent is invalid or does not correlate with the credit card number.";
	$code[241] = "The request ID is invalid. ";
	$code[242] = "You requested a capture through the API, but there is no corresponding, unused authorization record. Occurs if there was not a previously successful authorization request or if the previously successful authorization has already been used by another capture request.";
	$code[250] = "Error: The request was received, but there was a timeout at the payment processor.";
	$code[520] = "The authorization request was approved by the issuing bank but declined by CyberSource based on Smart Authorization settings.";
	return($code[$id]);
}

function map_cybersource_avs_codes($avs_code)
{
         $avs_code = strtoupper($avs_code);
                                                                                                                                                                                                        
         switch ($avs_code) {
                  case 'A': $avs_string = "Address (Street) matches, ZIP does not"; break;
                  case 'B': $avs_string = "Non-US Bank: Street Address Matches but postal code not verified"; break;
                  case 'C': $avs_string = "Non-US Bank: Street Address and postal code not verified"; break;
                  case 'D': $avs_string = "Non-US Bank: Street Address and postal code both match";break; // OK
                  case 'G': $avs_string = "Non-US Bank: does not support AVS"; break;  // OK
                  case 'M': $avs_string = "Non-US Bank: Street and Postal code both match"; break; // OK
                  case 'E': $avs_string = "AVS error"; break;
                  case 'N': $avs_string = "No Match on Address (Street) or ZIP"; break;
                  case 'P': $avs_string = "AVS not applicable for this transaction"; break; // OK
                  case 'R': $avs_string = "Retry System unavailable or timed out"; break;
                  case 'S': $avs_string = "Service not supported by issuer"; break; // OK
                  case 'U': $avs_string = "Address information is unavailable"; break; // OK
                  case 'W': $avs_string = "9 digit ZIP matches, Address (Street) does not"; break;
                  case 'X': $avs_string = "Exact AVS Match"; break; // OK
                  case 'Y': $avs_string = "Address (Street) and 5 digit ZIP match"; break; // OK
                  case 'Z': $avs_string = "5 digit ZIP matches, Address (Street) does not"; break;
                  case '1': $avs_string = "CyberSource: not supported card type or processor.";break;
                  case '2': $avs_string = "CyberSource: Unrecognized value returned from processor.";break;
                  default:  $avs_string = "Unknown"; break;
         }
         return $avs_string;
}

function map_cybersource_cvv_codes($cvv_code)
{
         $cvv_code = strtoupper($cvv_code);
                                                                                                                                                                                                        
         switch ($cvv_code) {
                  case 'I': $cvv_string = "CVV: Failed processor's data validation check"; break;
                  case 'M': $cvv_string = "CVV: Match"; break; // OK
                  case 'N': $cvv_string = "CVV: No Match"; break;
                  case 'P': $cvv_string = "CVV: Not Processed"; break; // OK
                  case 'S': $cvv_string = "CVV: Should have been present"; break;
                  case 'U': $cvv_string = "CVV: Issuer unable to process request"; break;
                  case 'X': $cvv_string = "CVV: Not supported by the card association"; break;
                  case '1': $cvv_string = "CVV: Not supported for this processor type"; break;
                  case '2': $cvv_string = "CVV: Unrecognized response from processor"; break;
                  case '3': $cvv_string = "CVV: No result code returned"; break;
                  default:  $cvv_string = "CVV: Unknown"; break;
         }
         return $cvv_string;
}


?>
