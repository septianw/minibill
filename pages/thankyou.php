<?php


//...... OC = Order Completed array
if (!isset($_SESSION['oc'])) 
{
	//...... Unset everything except my friends
	foreach($_SESSION as $key=>$val)
	{
		if (!preg_match('/oc|order_id|id|email|password/',$key))
		{	
			$session['oc'][$key] = $_SESSION[$key];
			unset($_SESSION[$key]);
		}
		else 
		{	
			$session[$key] = $val;
			unset($_SESSION[$key]);
		}
	}
	$session['oc']['purchase_date'] = date('r');

	$_SESSION = $session;
}


$Q="SELECT firstname,lastname,email FROM users WHERE id='$_SESSION[id]'";
list($firstname,$lastname) = mysql_fetch_row(mysql_query($Q));

$X->assign('firstname',$firstname);
$X->assign('lastname',$lastname);


$itm = 0;
if (isset($_SESSION['oc']['prods']))
{
	foreach($_SESSION['oc']['prods'] as $prod)
	{
		$_SESSION['oc']['prods'][$itm]['subTotal'] = $_SESSION['oc']['prods'][$itm]['price'] * $_SESSION['oc']['prods'][$itm]['quantity'];
		$itm++;
	}
}

if ($_SESSION['order_id'])
{
	$X->assign('title','Thank you!');
	$X->assign('email',$_SESSION['email']);
	$X->assign('prods',$_SESSION['oc']['prods']);
	$X->assign('grand_total',$_SESSION['oc']['grand_total']);
	$X->assign('password',$_SESSION['password']);

	$emailContent = $X->fetch('receipt_email.html');
}

if (!$_SESSION['mailed'])
{
	$_SESSION['mailed'] = 1;
	mail($_SESSION['email'],"{$config['company']['name']} Order Receipt","$emailContent","From: {$config['company']['contact_email']}\nContent-Type:text/html");
	unset($_SESSION['order_id']);
}

session_write_close();

?>
