<?php
error_reporting("E_ALL ^E_NOTICE");

//...... Always new orders
$DEV_MODE=0;

$FULL_MINIBILL_PATH = $argv[1];
//...... Assumes this is run via the full path in mod_shipping
$cwd = getcwd();

//...... Gets me into the main MiniBill root dir
if (chdir($FULL_MINIBILL_PATH))
{
	include('config.php');
	include($config['include_dir'].'functions.php');
	include($config['include_dir'].'dbConnect.php');
	include($config['include_dir'].'Smarty.class.php');
}
else die("Can't CHDIR!");


chdir($FULL_MINIBILL_PATH."plugins/mod_shipping/");
/*************************************************/
/* Load the configuration Vars from the database */
/*************************************************/
$Q="SELECT 
		id,variable,value 
	FROM 
		config";

$res = mysql_query($Q);

while($conf = mysql_fetch_row($res))
{
    list($id_val,$confvar,$confvalue) = $conf;
    $config[$id_val][$confvar] = $confvalue;
}

/***************************************/
/* Get all the orders between the time */
/***************************************/
$fd = fopen("lastChecked.txt","r");
if (!$fd) die("Can't open $cwd/lastChecked.txt file");
$lastTime = fgets($fd,1024);
if (!strlen($lastTime)) $lastTime = "0000-00-00 00:00:00";
fclose($fd);

if ($DEV_MODE) $lastTime = "0000-00-00 00:00:00";

$thisTime = date("Y-m-d H:i:s");
$fd = fopen("lastChecked.txt","w");
fputs($fd,$thisTime);
fclose($fd);

//...... Packaged Status
$Q="SELECT
		uniq_id 
	FROM 
		mod_shipping_data
	WHERE
		packaged 
	BETWEEN '$lastTime' AND '$thisTime'
	AND
		packaged != '0000-00-00 00:00:00'";
$cronRes = mysql_query($Q);
while(list($uniq_id) = mysql_fetch_row($cronRes)) 
{
	$p++;
	mailResults($uniq_id,"packaged.html");
}
if (!isset($p)) print "* No new orders PACKAGED\n";
else print "$p messages sent for PACKAGED\n";

//...... Shipped Status
$Q="SELECT
		uniq_id 
	FROM 
		mod_shipping_data
	WHERE
		shipped 
	BETWEEN '$lastTime' AND '$thisTime'
	AND
		shipped != '0000-00-00 00:00:00'";

$cronRes = mysql_query($Q);
while(list($uniq_id) = mysql_fetch_row($cronRes)) 
{
	$s++;
	mailResults($uniq_id,"shipped.html");
}
if (!isset($s)) print "* No new orders SHIPPED\n";
else print "$s messages sent for SHIPPED\n";

$lastTime24ago = date("Y-m-d H:i:s",strtotime($lastTime) - 86400);
$thisTime24ago = date("Y-m-d H:i:s",strtotime($thisTime) - 86400);

//...... Tracking Number Send (24 hours later)
$Q="SELECT
		uniq_id,tracking_no
	FROM 
		mod_shipping_data
	WHERE
		shipped 
	BETWEEN '$lastTime24ago' AND '$thisTime24ago'
	AND
		shipped != '0000-00-00 00:00:00'";
		
$cronRes = mysql_query($Q);
while(list($uniq_id,$tracking_no) = mysql_fetch_row($cronRes)) 
{
	$t++;
	mailResults($uniq_id,"tracking.html",$tracking_no);
}
if (!isset($t)) print "* No new sending TRACKING\n";
else print "$t messages sent for TRACKING\n";

function getTotals($prods)
{
	foreach($prods as $prod)
	{
		$gt += (floatval($prod['amount']) * intval($prod['quantity']));
	}
	return($gt);
}

function subTotal($prod)
{
	$prod['subTotal'] = (floatval($prod['amount']) * intval($prod['quantity']));
	return($prod);
}

function mailResults($uniq_id,$template,$tracking_no = 0)
{
	global $config;

	$X = new Smarty();
	$X->template_dir    =  $config['template_dir'];
	$X->compile_dir     =  $config['template_cache_dir'];

	if ($tracking_no) $X->assign('tracking_no',$tracking_no);

	$Q="SELECT 
			* 
		FROM 
			users,orders
		WHERE 	
			uniq_id='$uniq_id' 
		AND 
			id=user_id
		LIMIT 1";

	list($user) = getResults($Q,'ucitems');

	$Q="SELECT 
			* 
		FROM 
			mod_shipping
		WHERE 
			uniq_id='$uniq_id'
		LIMIT 1";

	list($shipTo) = getResults($Q);

	if (is_array($shipTo))
	{
		$user['address']	= $shipTo['address1']." ".$shipTo['address2'];
		foreach($shipTo as $key=>$value)
		{
			if (strlen($value)) $user[$key] = $value;
		}
	}

	$Q="SELECT 	
			title,quantity,amount FROM orders,products 
		WHERE 
			uniq_id='$uniq_id' 
		AND 
			orders.product_id=products.id";

	$prods = getResults($Q,"subTotal");
	
	$X->assign('config',$config);
	$X->assign('user',$user);
	$X->assign('prods',$prods);
	$X->assign('grand_total',getTotals($prods));
	$X->assign('order_id',$uniq_id);

	$content = $X->fetch($template);

	if (!$DEV_MODE)
	{
		mail("{$user['firstname']} {$user['lastname']} <{$user['email']}>",
			"Your order status (#$uniq_id)",
			$content,
			"From: {$config['company']['contact_email']}\r\nContent-Type:text/html\r\n","-f {$config['company']['contact_email']}");
	}
	else print "-------------------\n$content\n";
}

?>
