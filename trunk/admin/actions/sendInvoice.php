<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$l = 0;
$grand_total = 0;

$invoice = $_REQUEST['invoice'];

// For creating the link of products for invoice email
foreach($_REQUEST[prod] as $idx=>$value)
{
	if ($value[id]) $value[quantity] = ($value[quantity] == 0) ? 1 : $value[quantity];
	if ($value[quantity] > 0)
	{
		$Q="SELECT * FROM products WHERE id='$value[id]' LIMIT 1";
		$res = mysql_query($Q);
		$prodList[$l] = mysql_fetch_assoc($res);
		$grand_total += ($prodList[$l]['price'] * $value[quantity]);
		$prodList[$l]['item_desc'] = stripslashes($prodList[$l]['item_desc']);
		$prodList[$l]['quantity'] = $value['quantity'];

		//...... Create the prod_list of products and quantity
		$prod_list .= $prodList[$l]['id'];
		if ($value[quantity] > 1) $prod_list .= "-".$value[quantity];
		$prod_list .= ",";

		$l++;
	}
}

//..... Trim trailing comma
$prod_list = substr($prod_list,0,strlen($prod_list) -1);
list($firstname,$lastname) = split(' ',$invoice[toName]);

//...... Check to see if this user needs to be inserted into the database
$Q="SELECT * FROM users WHERE email='$invoice[emailTo]' LIMIT 1";
$res = mysql_query($Q);
$user = mysql_fetch_assoc($res);


//...... Generate a password if user doesn't have one all ready
$password = ($user[password]) ? $user[password] : substr($lastname,0,4).substr(md5(time()),0,4);

$invoice = $_POST[invoice];
if (mysql_num_rows($res) ==0 )
{
	$Q="INSERT INTO users SET 
		email='$invoice[emailTo]',
		firstname='".addslashes(ucfirst($firstname))."',
		lastname='".addslashes(ucfirst($lastname))."',
		password='".addslashes($password)."',
		user_stamp=NOW()";
	mysql_query($Q);
	$user[id] = mysql_insert_id();
}

//...... Add order to database
$o = new OrderClass($user[id]);
foreach($prodList as $prod)
	$o->addItem($prod[id],$prod[quantity],$prod[price],$invoice[due]);

$order_id = $o->postOrder();

$X->assign('config',$config);
$X->assign('password',$password);
$X->assign('user',$user);
$X->assign('invoice',$invoice);
$X->assign('prod_list',$prod_list);
$X->assign('items',$prodList);
$X->assign('order_id',$order_id);
$X->assign('grand_total',number_format($grand_total,2));
$X->assign('date',date('M d Y'));


if (!strlen($invoice[due]))
	$X->assign('duedate',date('M d Y'));
else 
	$X->assign('duedate',date('M d Y',strtotime($invoice[due])));

$invoiceMsg = $X->fetch("db:".$config['invoice']['template']);

//...... Stephen Lawrence email contribution
send_mail($config['invoice']['friendly'],$config['invoice']['email'],$invoice['toName'],$invoice['emailTo'],$config['company']['name']." Invoice",stripslashes($invoiceMsg),'html');

//...... send_mail Function contributed by Stephen Lawrence
function send_mail($myname, $myemail, $contactname, $contactemail,
$subject, $message, $type='plain') {
 $headers .= "MIME-Version: 1.0\n";
 $headers .= "Content-type: text/$type; charset=iso-8859-1\n";
 $headers .= "X-Priority: 3\n";
 $headers .= "X-MSMail-Priority: Normal\n";
 $headers .= "X-Mailer: php\n";
 $headers .= "From: \"".$myname."\" <".$myemail.">\n";

#print "<pre>$headers</pre>$message";
#exit();

 return(mail("\"".$contactname."\" <".$contactemail.">", $subject,
$message, $headers));
}


$msg = base64_encode("#00A000|#FFFFFF|Invoice Sent.");
header("Location: index.php?page=invoice&msg=$msg");

?>
