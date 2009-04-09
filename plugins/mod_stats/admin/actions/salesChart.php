<?php


$thisWeekEnd = date("Y-m-d H:i:s",time());
$thisWeekStart =  WeekStart($thisWeekEnd);

$lastWeekStart	= weekStart(date("Y-m-d",strtotime($thisWeekStart) - 86400));
$lastWeekEnd	= date("Y-m-d 23:59:59",strtotime($lastWeekStart) + (86400 * 6));

$thisWeekData = getOrdersBetween($thisWeekStart,$thisWeekEnd);
$lastWeekData = getOrdersBetween($lastWeekStart,$lastWeekEnd);

$X->assign('thisWeekStart',date("Y-m-d",strtotime($thisWeekStart)));
$X->assign('lastWeekStart',date("Y-m-d",strtotime($lastWeekStart)));
$X->assign('thisWeekEnd',date("Y-m-d",strtotime($thisWeekEnd)));
$X->assign('lastWeekEnd',date("Y-m-d",strtotime($lastWeekEnd)));

$X->assign('thisWeekData',$thisWeekData);
$X->assign('lastWeekData',$lastWeekData);

$X->display("salesChart.html");

function getOrdersBetween($thisWeekStart,$thisWeekEnd)
{
	$start = strtotime($thisWeekStart);

	//...... Pads the order data with zeros.
	for ($x = 1;$x <= 7;$x++)
	{
		$newDate = $start + (86400 * $x);
		$thisWeek[date("Y-m-d",$newDate)] = '<null/>';
	}

	$Q="SELECT 
			COUNT(DISTINCT(uniq_id)) AS orders,
			DATE_FORMAT(date_purchased,'%Y-%m-%d') AS date_purchased
		FROM 
			orders
		WHERE
			date_purchased
		BETWEEN
			'$thisWeekStart' AND '$thisWeekEnd'
		GROUP BY date_purchased";

	$res = mysql_query($Q);
	while($orders = mysql_fetch_assoc($res))
	{
		$thisWeek[$orders['date_purchased']] = 
		'<number>'.$orders['orders']."</number>";
	}
	return($thisWeek);
}

function WeekStart($thisTime )
{
	$thisTime = strtotime($thisTime);

	while( date('w',$thisTime) != 0)
	{
	   $thisTime -= 86400;
	}
	return date('Y-m-d 00:00:00',$thisTime);
}

?>
