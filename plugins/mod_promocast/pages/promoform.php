<?php

	list($user_id,$time) = explode("|",data_decrypt($_REQUEST['id'],'elijah'));

	// Don't do anything with the TIME string until necessary ... 

	//..... Leading Zeros
	$_SESSION['id'] = sprintf("%010d",$user_id);

    if (($_GET['pay'] == 'now' && strlen($_GET['order_id']))    ||
    (strlen($_GET['email']))                                    ||
    $_GET['order_group']                                        ||
    isset($_REQUEST['order']))
	{
		/**********************************/
		/* This comes from the index page */
		/**********************************/
        //...... If they are making a payment from their login
        if ($_SESSION['id'])
        {
			$X->assign('valid_password','1');
            $X->assign('order_id',$_GET['order_id']);
            session_unregister('order_id');

			$Q="SELECT * FROM users WHERE id='$_SESSION[id]' LIMIT 1";
			$userInfo = mysql_fetch_assoc(mysql_query($Q));
			print mysql_error();

            foreach ($userInfo as $key=>$value)
            {
                if (preg_match("/cardnum|cc_exp_yr|cc_exp_mo/",$key)) continue;
                $X->assign($key,stripslashes($value));
            }
        }
	}

//...... Regular orders
if (!$_REQUEST['uniq_id'])
{
	$prodlist = $_REQUEST['order'];

	//...... If we have product info from a previous session
	//...... This makes it work like a shopping cart
	//...... add products, keep shopping etc.
	if ($_SESSION['prods'])
	{
		$oid = 0;
		foreach($_SESSION['prods'] as $prod)
		{
			//...... Do a last ditch stock check effort.
			$Q="SELECT title,stock FROM products WHERE id='$prod[id]' LIMIT 1";
			list($title,$inStock) = mysql_fetch_row(mysql_query($Q));

			if ($inStock < $prod['quantity'] && $inStock > 0)
			{
				$sysMsg->addMessage('Stock has been depeleted, your order quantity and price has been changed to reflect this.');
				updateProd($prod['id'],'quantity',$inStock);
			}
			elseif($inStock == 0) 
			{
				$sysMsg->addMessage('We are currently out of '.$title.'.');
				unset($_SESSION['prods'][$oid]);
				
				//...... This is SOO Nacker
				updateProd($prod['id'],'quantity','*');
				$quantity[$prod[id]] = 0;	
			}
			
			$prodlist .= ','.$prod['id']."-".$prod['quantity'];
			$oid++;
		}
	}
	else $_SESSION['prods'] = array();

	//...... Making a pre-payment from their history window
	if ($_GET['order_id'])
	{
		$Q="SELECT * FROM orders WHERE order_id='$_GET[order_id]' AND user_id='$_SESSION[id]' LIMIT 1";
		$preOrder = mysql_fetch_assoc(mysql_query($Q));

		$prodlist = $preOrder['product_id'].'-'.$preOrder['quantity'];

		$product_id	= $preOrder['product_id'];
		$price		= $preOrder['amount'];
	}
		
	//...... If products have a hyphen, the number following is the quantity
	//...... E.g: "order=1-4" will display item 1 with a quantity of 4.
	if (ereg("-",$prodlist))
	{
		$_REQUEST['order'] = '';
		foreach(explode(",",$prodlist) as $value)
		{
			list($k,$v) = explode("-",$value);
			if ($k)
			{
				$_REQUEST['order'] .= "$k,";
				$quantity[$k] = $v;
			}
		}
		$_REQUEST['order'][strlen($_REQUEST['order'])-1] = ' ';
	}

	$grand_total = 0;

	//...... This is here if they are re-ordering a product in their history
	if ($_SESSION['id'])
	{
		$Q="SELECT * FROM users WHERE id='$_SESSION[id]' LIMIT 1";
		$ures = mysql_query($Q);
		$user = mysql_fetch_assoc($ures);
		$X->assign('user',$user);
	}

	$Q="SELECT * FROM products WHERE id IN (".addslashes($_REQUEST['order']).")";
	$res = mysql_query($Q);

	if ($res)
	{
		while($info = mysql_fetch_assoc($res))
		{
			//...... Set quantity default to 1 or value if set
			$info['quantity'] = ($quantity[$info[id]]) ?  $quantity[$info[id]] : 1;

			//...... Make sure we get the correct info from the last order if applicable
			if ($info['id'] == $product_id) $info['price'] = $price;

			updateProd($info['id'],'',$info);

			$grand_total += ($info['price'] * $info['quantity']);
		}
	}
}
//...... We have a previously set up order group of products and pricing
//...... Usually used when sending invoices
else
{
	$Q="SELECT * FROM orders WHERE uniq_id='".addslashes($_REQUEST['uniq_id'])."'";
	$res = mysql_query($Q);
	while($info = mysql_fetch_assoc($res))
	{
		$Q="SELECT * FROM products WHERE id='$info[product_id]' LIMIT 1";
		$pres = mysql_query($Q);
		$prod = mysql_fetch_assoc($pres);

		$info['price'] = $info['amount'];
		$info['title'] = $prod['title'];
		$info['stock'] = $prod['stock'];
		$user_id = $info['user_id'];

		if ($info['status'] != 'paid')
		{
			updateProd($product_id,'price',$info['amount']);
			updateProd($product_id,'title',$prod['title']);
			updateProd($product_id,'stock',$prod['stock']);
			$grand_total += ($info['price'] * $info['quantity']);
		}
	}

	//...... Get the user information for pre-population
	$Q="SELECT * FROM users WHERE id='$user_id' LIMIT 1";
	$user = mysql_fetch_assoc(mysql_query($Q));

	//...... This is so we don't have to have their card info in the order form
	//...... pre-populated (Remove if you don't care, don't forget to data_decrypt)
	unset($user['cardnum']);
	unset($user['cc_exp_yr']);
	unset($user['cc_exp_mo']);

	if (isset($user['id']))
	{
		foreach($user as $key=>$value)
		{
			$X->assign($key,$value);
		}
	}
	$X->assign('uniq_id',$_REQUEST['uniq_id']);
}

$X->assign('title','Finalize Your Order');
$X->assign('comments',$_REQUEST[comments]);
$X->assign('plugin',$plugin);
$X->assign('grand_total',number_format($grand_total,2));
$X->assign('prods',$_SESSION['prods']);


//...... Have to check if is set, or xajax will kill us here.
if (isset($grand_total)) $_SESSION['grand_total'] = $grand_total;

$hijackTemplate=1;

?>
