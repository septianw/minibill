<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the GPL                               */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

class OrderClass
{
	var $demo = 0;
	var $uniq_id;
	var $prods = array();
	var $config = array();
	var $user_id = 0;
	var $gateway;

    function OrderClass($user_id,$uniq_id = 0,$config = 0,$gateway = '')
    {
        if (!$user_id) return(0);
        $this->gateway = $gateway;

        //...... PRE-Generate a uniqe id for this order set
        $this->uniq_id = ($uniq_id) ? $uniq_id : OrderClass::genUniq_id($user_id);
        $this->config   = $config;
        $this->user_id                                  = $user_id;
        return(1);
    }

    function genUniq_id($user_id)
    {
            $uniq_id = substr(time(),-7,7);
            $uniq_id .= rand(100000,10000);

            return(trim($user_id,'0').".$uniq_id");
    }



	// Private
	//...... Sets the last purchase date for a user
	function updateUserPurchaseStamp($user_id)
	{
		$Q="UPDATE users SET last_purchase_stamp=NOW() WHERE id='$user_id'";
		if ($this->demo) print $Q."<br />\n";
		else mysql_query($Q);
		if (mysql_error()) print $Q.mysql_error();
	}

	//...... Can remove this from "functions.php" now
	function recurVal($is_recurring = 'no')
	{
		//...... Ultimately load this from a config table
		if (preg_match("/no/i",$is_recurring))			return(0);
		elseif (preg_match("/daily/i",$is_recurring))	return(1);
		elseif (preg_match("/weekly/i",$is_recurring))	return(7);
		elseif (preg_match("/monthly/i",$is_recurring)) return(30);
		elseif (preg_match("/quarter/i",$is_recurring)) return(92);
		elseif (preg_match("/semi/i",$is_recurring))	return(182);
		elseif (preg_match("/yearly/i",$is_recurring))	return(365);
		else return(0);
	}

	//...... Get the uniq group ID from the order ID
	function getUniqIdFromOrder($order_id)
	{
		$Q="SELECT uniq_id 
			FROM orders 
			WHERE id='$order_id' 
			LIMIT 1";
		list($uniq_id) = mysql_fetch_row(mysql_query($Q));
		return($uniq_id);
	}

	//...... Count the number of orders in a group
	function getGroupCount($order_id=0,$uniq_id=0)
	{
		//..... Get the uniq id from the order_id	
		if ($order_id) 
		{
			$uniq_id = OrderClass::getUniqIdFromOrder($order_id);
		}
		$Q="SELECT COUNT(*)
			FROM orders 
			WHERE uniq_id='$uniq_id'";
		list($order_count) = mysql_fetch_row(mysql_query($Q));
		return($order_count);
	}

	//...... Delete orders
	//...... If uniq id, delete order group
	//...... If order id delete only single order item
	//...... If both, delete single order item;
	//...... Returns the number of orders left in the order group;
	function delOrder($order_id=0,$uniq_id=0)
	{
		
		$order_count = OrderClass::getGroupCount($order_id,$uniq_id);
	
		$Q="DELETE FROM orders WHERE";
		if 		(!$order_id)	$Q .= " uniq_id='$uniq_id' ";	
		elseif	(!$uniq_id)		$Q .= " id='$order_id' LIMIT 1";
		else					$Q .= " id='$order_id' AND uniq_id='$uniq_id' LIMIT 1";
		mysql_query($Q);
		return($order_count - mysql_affected_rows());
	}

	//...... Item is an array, that contains whatever info you need it to 
	//...... Required is $item[order_id];
	function updateItem($item)
	{
		if (isset($item['order_id']))
		{
			$Q="SELECT *
				FROM orders where id='$item[order_id]' 
				LIMIT 1";

			$order = mysql_fetch_assoc(mysql_query($Q));

			if (!isset($item['recurring']))		$item['recurring']	= $order['recurring'];
			if (!isset($item['quantity']))		$item['quantity']	= $order['quantity'];
			if (!isset($item['amount']))		$item['amount']		= $order['amount'];
			if (!isset($item['payment_due']))	$item['payment_due']= $order['payment_due'];
			if (!isset($item['status']))		$item['status']		= 'paid';
		
			$due_date = strtotime($item['payment_due']);
			$item['payment_due'] =  date('Y-m-d',$due_date + (86400 * $item['recurring']));

			$Q="UPDATE orders 
				SET status='paid',recurring='0' 
				WHERE id='$item[order_id]' LIMIT 1";
			$res = mysql_query($Q);

			if ($item['recurring'])
			{
				$this->addItem($order['product_id'],$item['quantity'],$item['amount'],$item['payment_due'],$item['order_id']);
			}
		}
		else return(0);
	}

	//...... Add an item to the order group
	function addItem($prod_id,$quantity = 1,$override_price = 0,$due = 0,$order_id = 0)
	{
		//...... Get priduct info
		$Q="SELECT * FROM products WHERE id='$prod_id' LIMIT 1";
		$res = mysql_query($Q);	
		$prod = mysql_fetch_assoc($res);

		//...... If we don't have a due date set, set it for recurring
		$payment_due = ($due == 0) ? date('Y-m-d',time() + (86400 * OrderClass::recurVal($prod['is_recurring']))) : date('Y-m-d',strtotime($due));

		//...... Overrides
		$prod['price']		= ($override_price > 0) ? $override_price : $prod['price'];
		$prod['recurring']	= OrderClass::recurVal($prod['is_recurring']);
		$prod['quantity']	= ($quantity > 0) ? $quantity : 1;
		$prod['due']		= $payment_due;
		if ($order_id) $prod['order_id']	= $order_id;

		//...... Coupon support probably goes here
		$this->prods[] = $prod;
	}

	function insertOrder($prod,$status = 'paid',$fail = 0)
	{
		$Q="INSERT INTO orders SET 
			product_id='{$prod['id']}',
			uniq_id='{$this->uniq_id}',
			amount='{$prod['price']}',
			quantity='{$prod['quantity']}', 
			date_purchased=NOW(), 
			user_id='{$this->user_id}', 
			last_billed=NOW(), 
			payment_due='{$prod['due']}', 
			gateway='{$this->gateway}',
			recurring='{$prod['recurring']}', 
			status='{$status}',
			mailer='',
			ip='{$_SERVER['REMOTE_ADDR']}',
			payfail='{$fail}'";

		if ($this->demo) print $Q."<br />\n";
		else mysql_query($Q);

		//...... Returns the order id
		return(mysql_insert_id());
	}

	//...... Posts the order items to the orders table
	function postOrder($demo = 0)
	{
		$this->demo = $demo;

		OrderClass::updateUserPurchaseStamp($this->user_id);

		foreach($this->prods as $prod)
		{
			//...... how is this ever even going to happen?
			//...... From a manual purchase from user login?
			if ($prod['id'])
			{
				$Q="UPDATE orders SET 
					last_billed=NOW(),
					recurring='0',
					status='paid'
					WHERE id='$prod[id]' 
					LIMIT 1";
				if ($this->demo) print $Q."<br />\n";
				else mysql_query($Q);
				//if (mysql_error()) print "[$Q]<br />".mysql_error()." ----";
			}

			if ($this->depleteStock($prod['id'],$prod['quantity']))
			{
				//...... Have some sort of stock threshold.
				//...... Mail somebody about stock being gone.
			}

			//...... Maybe check contract duration?
			//***** SHOULD INSERT AN ORDER FOR NEXT RECURRING as UNPAID/DUE
			if (!$order_id) 					$status = 'paid';
			if ($prod[is_recurring] == 'yes')	$status = 'due';

			$this->insertOrder($prod,$status);
		}
		return($this->uniq_id);
	}

	function depleteStock($prod_id,$quantity)
	{
		//...... UPDATE my order stock
		$Q="SELECT stock FROM products WHERE id='$prod_id' LIMIT 1";
		list($inStock) = mysql_fetch_row(mysql_query($Q));
		if (mysql_error()) print $Q.mysql_error();

		$stock = $inStock - $quantity;
		
		if ($inStock > -1 && $inStock != 0)
		{
			$Q="UPDATE products SET stock='$stock' WHERE id='$prod_id' LIMIT 1";
			mysql_query($Q);
		}
		if (mysql_error()) print $Q.mysql_error();

	}
}

?>
