<?php

/*****************************
 * Required key->value pairs *
 *****************************
user[email]
user[firstname]		= The first name of the customer
user[lastname]		= The lastname, or Surname of the customer
user[address]		= Full street address, including apt # etc.
user[city]			= Full city name
user[state]			= full state name, or 2 letter code
user[country]		= 3 letter country code
user[zipcode]		= Zip Code / Postal code

//...... Optional
user[password]		= Allows user to log in to check shipping status (our system)
user[comments]		= If user has any special comments or requests in shipping

prods[0][id]		= product id 
prods[0][quantity]	= number of products in order

orderId				= The order ID from your system
key					= the assigned 32 char MD5 hash or key

*/

$Q="SELECT 
		company,email 
	FROM 
		mod_interface 
	WHERE 
		remote_key='".addslashes($_REQUEST['key'])."' 
	LIMIT 1";

list($key_valid,$email) = mysql_fetch_row(mysql_query($Q)); 


if ($key_valid)
{

	//...... Plug this into mod_affiliate as well
	if (is_on($config['plugins']['mod_affiliate']))
	{
		$modAF = "../mod_affiliate/include/affiliate.class.php";
		include($modAF);
		$Q="SELECT
				id
			FROM
				mod_affiliate
			WHERE
				remote_key='{$_REQUEST['key']}'
			LIMIT 1";
		list($afid) = mysql_fetch_row(mysql_query($Q));
	}

	$Q="UPDATE mod_interface SET hits=hits+1 WHERE remote_key='".addslashes($_REQUEST['key'])."' LIMIT 1";
	mysql_query($Q);

	$user	= $_REQUEST['user'];

	if (   isset($user['firstname']) 
		&& isset($user['email'])
		&& isset($user['lastname'])
		&& isset($user['address'])
		&& isset($user['city'])
		&& isset($user['state'])
		&& isset($user['country'])
		&& isset($user['zipcode'])) $userValid = 1;
	else $error = 'User shipping information incomplete,';

	if ($userValid)
	{
		$prods	= $_REQUEST['prods'];

		//...... Assumes sanity check on their side
		//...... Check to see if we alrady have an id from this user

		//...... If Need NOT get their real email address .. a hash of their email would work.
		$Q="SELECT id 
			FROM users 
			WHERE email='".addslashes($user['email'])."'";
		list($id) = mysql_fetch_row(mysql_query($Q));
		if ($id) $user['id'] = $id;

		if (!isset($user['password'])) $user['password'] = rand(1000,9999);
		$Q=buildSet($user,'id','users');

		mysql_query($Q);

		if (!$id) $id = mysql_insert_id();

		$o = new OrderClass($id,'',$config,'');
		if ($prods)
		{
			foreach($prods as $prod)
			{
				//...... Get the product ID from their remote_prod_id
				$Q="SELECT 
						id 
					FROM 	
						mod_interface_prodLookup 
					WHERE 	
						remote_prod_id='".$prod['id']."' 
					LIMIT 1";
				list($prod_id) = mysql_fetch_row(mysql_query($Q));

				if ($prod_id)
				{
					if (!$prod['quantity']) $prod['quantity']	= 1;
					$o->addItem($prod_id, $prod['quantity'], $prod['amount']);
					$grand_total += $prod['amount'];
				}
				else $error .= "Invalid product id [".$prod['id']."],";

			}
		}
		else $error .= 'No products specified,';
	}
	if (!$error)
	{
		//...... Lookup to maintain order id integrity
		$Q="INSERT INTO 
				mod_interface_data 
			SET 
				remote_order_id='".addslashes($_REQUEST['orderId'])."',
				uniq_id='$o->uniq_id',
				remote_key='".addslashes($_REQUEST['key'])."'";
		mysql_query($Q);

		print "OK:$o->uniq_id";
		if (!$error) 
		{
			$o->postOrder();
			
			//...... mod_affiliate hooks
			if ($afid)
			{
				$af = new affiliate($afid);
				$rip = $af->getCommission($grand_total);
				$af->setOrder($o->uniq_id,$user['id'],$rip);
			}
		}
	}
	else print "ERROR:".substr($error,0,strlen($error) - 1);
}
else
{
	print "invalid key";
}

exit();
?>
