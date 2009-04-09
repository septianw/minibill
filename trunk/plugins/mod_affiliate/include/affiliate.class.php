<?php
class affiliate
{
	var $afid = 0;

	function affiliate($afid)
	{
		$this->afid = $afid;
	}

	function getCommission($grand_total)
	{
		$Q="SELECT 
				rip
			FROM 
				mod_affiliate
			WHERE 
				id='$this->afid'";

		list($rip) = mysql_fetch_row(mysql_query($Q));
		//...... If it is a percentage commission
		return (preg_match('/%/',$rip ) ? floatval($grand_total) * (intval($rip) / 100) : $rip);
	}

	function setOrder($order_id,$user_id,$rip_total)
	{
		$Q="INSERT INTO 
				mod_affiliate_data 
			SET
				afid='$this->afid',
				uniq_id='$order_id',
				user_id='$user_id',
				grand_total='$rip_total',
				date=NOW()";
		mysql_query($Q);
		return(mysql_affected_rows());
	}

}

?>
