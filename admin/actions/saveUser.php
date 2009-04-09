<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$user = $_POST['user'];
$card = $_POST['card'];

foreach($user as $key=>$value)
{
	$set  .= "$key='".addslashes($value)."',";
}

//...... Check to see if credit card information has changed -- 
//...... should prolly luhn mod 10 the cc num argh

//...... Encrypt the credit card info here:
if (strlen($card[cardnum]) > 7) $set    .= "cardnum = '".data_encrypt($card['cardnum']  ,$config['secret_key'])."',";
if (strlen($card[cc_exp_yr]) == 4) $set .= "cc_exp_yr='$card[cc_exp_yr]',";
if (strlen($card[cc_exp_mo]) == 2) $set .= "cc_exp_mo='$card[cc_exp_mo]',";

$set[strlen($set)-1] = ' ';

//...... Are we UPDATING a user? or NEW?
if ($_REQUEST['id'])
	$Q="UPDATE users SET $set WHERE id='{$_REQUEST['id']}' LIMIT 1";
else 
	$Q="INSERT INTO users SET $set";

mysql_query($Q);

if (!$_REQUEST['id']) $_REQUEST['id'] = mysql_insert_id();

if (mysql_error())
	$msg = base64_encode("#A00000|#FFFFFF|Something barfed: ".mysql_error());
else
	$msg = base64_encode("#00A000|#FFFFFF|Information saved.");

$redirect_to = ("index.php?page=editUser&id={$_REQUEST['id']}&msg=$msg");

?>
