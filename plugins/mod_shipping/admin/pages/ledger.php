<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

$X->assign('title','SALES LEDGER');

//...... Ledeger Search parameters
$phrase = $_REQUEST[search][phrase];
if (!$phrase)
{
	$phrase = date('Y-m-d');
}

$X->assign('SELECT'.$_REQUEST[search][type],'SELECTED');
//..... Get all the orders for today
$sdate = date('Y-m-d 00:00:00',strtotime($phrase));
$edate = date('Y-m-d 23:59:59',strtotime($phrase));

//...... Ledger query/search types
switch ($_REQUEST[search][type])
{
	case "all":
		$Q="SELECT user_id,users.id,uniq_id,email,firstname,lastname,SUM(amount * quantity) AS amount,date_purchased,last_billed,SUM(quantity) AS quantity FROM orders,users 
			WHERE orders.user_id=users.id
			GROUP BY uniq_id";
		break;
	case "name":
		$Q="SELECT user_id,users.id,uniq_id,email,firstname,lastname,SUM(amount * quantity) AS amount,date_purchased,last_billed,SUM(quantity) AS quantity FROM orders,users 
			WHERE (users.firstname LIKE '%".addslashes($phrase)."%' 
			OR users.lastname LIKE '%".addslashes($phrase)."%')
			AND orders.user_id=users.id
			GROUP BY uniq_id";
		break;
	case "email" : 
		$Q="SELECT user_id,users.id,uniq_id,email,firstname,lastname,SUM(amount * quantity) AS amount,date_purchased,last_billed,SUM(quantity) AS quantity FROM orders,users 
			WHERE users.email like '%".addslashes($phrase)."%' 
			AND orders.user_id=users.id
			GROUP BY uniq_id";
		break;
	default:
		//$Q="SELECT * FROM orders,users WHERE last_billed BETWEEN '$sdate' AND '$edate' AND user_id=id";
		$Q="SELECT user_id,users.id,uniq_id,email,firstname,lastname,SUM(amount * quantity) AS amount,date_purchased,last_billed,SUM(quantity) AS quantity FROM orders,users 
		WHERE last_billed 
		BETWEEN '$sdate' AND '$edate' 
		AND user_id=users.id 
		GROUP BY uniq_id;";
}

//...... Build Ledger
$res = mysql_query($Q);
while($order = mysql_fetch_assoc($res))
{
	$grand_total += $order[amount];
	$order[amount] = number_format($order[amount],2);
	$orders[] = $order;
}

//...... Build Calendar
$end_of_month = date('t');
$fulldate = date('Y-m-');
//...... Pre-populate with "-"
for ($x = 1;$x <= $end_of_month;$x++)
{
	$fd = $fulldate.sprintf("%02d",$x);
	$month[$fd] = '-';
}

$sdate = date('Y-m-01 00:00:00');
$edate = date('Y-m-t 23:59:59');
$Q="SELECT amount,last_billed FROM orders WHERE last_billed BETWEEN '$sdate' AND '$edate'";
$res = mysql_query($Q);

while($days = mysql_fetch_assoc($res))
{
	if ($days[last_billed])
	{
		$fd = date('Y-m-d',strtotime($days['last_billed']));
		$month[$fd] += $days[amount];
	}
}

$X->assign('backref',$_SERVER[REQUEST_URI]);
$X->assign('month',$month);
$X->assign('orders',$orders);
$X->assign('grand_total',number_format($grand_total,2));

?>
