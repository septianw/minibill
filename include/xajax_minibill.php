<?php

$xajax->cleanBufferOff();
//$xajax->registerFunction('paymentForm',XAJAX_GET);
$xajax->registerFunction('paymentForm');

#$xajax->statusMessagesOn();
#$xajax->errorHandlerOn();
#$xajax->bDebug = 1;

function paymentForm($formType)
{
	global $config,$X;

	$data = $X->fetch('orderform_'.$formType.'.html');

    $r = new xajaxResponse();

    $r->addAssign('orderType','innerHTML',$data.$set);
	$_SESSION['payment_type'] = $formType;
    return($r);
}

?>
