<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$user = $_POST['user'];
$card = $_POST['card'];

if ($DEMO_MODE && $_SESSION['id'] == 1)
{
	$user['email'] = 'demo@ultrize.com';
	$user['password'] = 'demo';
}
$set = '';

foreach($user as $key=>$value)
{
	$set  .= "$key='".addslashes($value)."',";
}

//...... Check to see if credit card information has changed -- 
//...... should prolly luhn mod 10 the cc num argh

//...... Encrypt the credit card info here:
if (strlen($card[cardnum]) > 7) $set    .= "cardnum = '".data_encrypt($card['cardnum']  ,$config['secret_key'])."',";
if (strlen($card[cc_exp_yr]) == 4) $set .= "cc_exp_yr='".$card['cc_exp_yr']."',";
if (strlen($card[cc_exp_mo]) == 2) $set .= "cc_exp_mo='".$card['cc_exp_mo']."',";

$set[strlen($set)-1] = ' ';

$Q="UPDATE users SET $set WHERE id='$_SESSION[id]' LIMIT 1";
mysql_query($Q);
$msg = base64_encode("#00A000|#FFFFFF|Your information has been updated");
$redirect_to = ("index.php?page=myinfo&msg=$msg");

?>
