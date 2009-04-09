<?php
$_SESSION['id'] = sprintf("%010d",intval($_SESSION['id']));

foreach($_SESSION['prods'] as $num=>$prod)
{
	$creditProd[$prod['id']] = $prod['quantity'];	
}

foreach($creditProd as $prod_id=>$quantity)
{
	$Q="SELECT 
			credits,
			promoCredits
		FROM 
			mod_promocast 
		WHERE 
			prod_id='$prod_id'";
	list($credits,$promoCredits) = mysql_fetch_row(mysql_query($Q));

	$promoCredits = (intval($promoCredits) * intval($quantity));
	$totalCredits = (intval($credits) * intval($quantity));

	$Q="UPDATE
			promocast.user_credits
		SET
			promoCredits=promoCredits + $promoCredits,
			credits=credits + $totalCredits
		WHERE
			user_id='{$_SESSION['id']}'";
	mysql_query($Q);

	//...... New person buying new credits
	if (!mysql_affected_rows())
	{
		$Q="INSERT INTO 
				promocast.user_credits
			SET 
				promoCredits=promoCredits+$promoCredits,
				credits=credits+$totalCredits,
				user_id='{$_SESSION['id']}'";
		mysql_query($Q);
	}

}

//...... Check to see if user exists in promocast
$Q="SELECT 
		id 
	FROM 
		promocast.users 
	WHERE 
		email='{$_SESSION['email']}' 
	LIMIT 1";

list($user_id) = mysql_fetch_row(mysql_query($Q));

if (!$user_id)
{
	$Q="INSERT INTO 
			promocast.users
		SET 
			id='{$_SESSION['id']}',
			email='{$_REQUEST['user']['email']}',
			password='{$_REQUEST['user']['password']}',
			firstname='{$_REQUEST['user']['firstname']}',
			lastname='{$_REQUEST['user']['lastname']}',
			address='{$_REQUEST['user']['address']}',
			city='{$_REQUEST['user']['city']}',
			state='{$_REQUEST['user']['state']}',
			country='{$_REQUEST['user']['country']}',
			zipcode='{$_REQUEST['user']['zipcode']}',
			phone='{$_REQUEST['user']['phone']}',
			dateAdded=NOW()";

	mysql_query($Q);

	//...... User Config Defaults
	$QRY[]="c_desc='Send Reports Email Address',
		c_id='report_email',
		c_value='{$_REQUEST['user']['email']}'";

	$QRY[]="c_desc='Message Link Tracking',
		c_id='track_links',
		c_value='1'";

	$QRY[]="c_desc='No Waiting For Message Auth',
		c_id='user_nowait',
		c_value='0'";

	foreach($QRY as $Q)
	{
		mysql_query("INSERT INTO promocast.user_conf SET c_u_id='{$_SESSION['id']}',$Q");
	}

}

//...... Check to see if they have any default forms
$Q="SELECT 
		* 
	FROM 
		promocast.mod_forms 
	WHERE 
		user_id='{$_SESSION['id']}' 
	LIMIT 1";
$res = mysql_query($Q);

//...... If they don't have forms, give them the default (0 user)
if (!mysql_num_rows($res))
{
	//...... Create default forms for users
	//...... Select all "0" user forms (any user)
	$Q="SELECT 
			* 
		FROM 
			promocast.mod_forms 
		WHERE 
			user_id='0000000000'";
	$res = mysql_query($Q);

	while($form = mysql_fetch_assoc($res))
	{
		$oldForm = $form['id'];
		unset($form['id']);
		$form['user_id'] = $_SESSION['id'];
		$Q=buildSet($form,'id','promocast.mod_forms');
		mysql_query($Q);
		$form['id'] = mysql_insert_id();

		//...... Get all the questions
		$Q="SELECT 
				*
			FROM 
				promocast.mod_forms_question
			WHERE
				form_id='$oldForm'";
		$res = mysql_query($Q);

		while($ques = mysql_fetch_assoc($res))
		{
			unset($ques['id']);
			$ques['form_id'] = $form['id'];
			$Q=buildSet($ques,'id','promocast.mod_forms_question');
			mysql_query($Q);
		}

	}
}

/* $_SESSION structure:
Array
(
    [prods] => Array
        (
            [1] => Array
                (
                    [id] => 2
                    [title] => Lions Fuel
                    [item_desc] => The best post workout recovery suppliment money can buy.
                    [category_id] => 2
                    [price] => 99.00
                    [weight] => 0.0
                    [stock] => 898
                    [is_recurring] => Monthly
                    [contract] => 0
                    [item_code] => 
                    [quantity] => 1
                )

        )

    [grand_total] => 99
    [lastUrl] => /dev/index.php?page=orderform&order=2
    [total_items] => 1
    [user] => Array
        (
            [id] => 0000000022
            [email] => demo@ultrize.com
            [password] => test
            [firstname] => Matthew
            [lastname] => Frederico
            [address] => 1234 test street
            [city] => Corpus Christi
            [state] => TX
            [country] => US
            [zipcode] => 78413
            [phone] => 8016809713
            [comments] => 
            [orderFrom] => 
            [cardnum] => DmxVMlUyBWFSZQpkXTNXYVw1WzMCZgc3Czs=
            [cc_exp_yr] => 0
            [cc_exp_mo] => 0
            [user_stamp] => 2006-03-13 12:26:58
            [last_purchase_stamp] => 2006-06-20 12:26:10
        )

    [shipping] => Array
        (
            [user_id] => 22
            [firstname] => 
            [lastname] => 
            [address1] => 
            [address2] => 
            [city] => 
            [state] => 
            [zipcode] => 
            [country] => 
            [phone] => 
            [fax] => 
            [cell] => 
            [last_updated] => 0000-00-00 00:00:00
        )

    [id] => 0000000022
    [email] => demo@ultrize.com
    [password] => test
    [order_id] => 082462817939
)
*/
