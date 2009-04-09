<?php

$Q="SELECT * 
	FROM 
		mod_shipping_data 
	WHERE 
		uniq_id='{$_REQUEST['uniq_id']}' 
	LIMIT 1";

$plugin[$thisPlugin][$thisFile]['pos']      = 'below';

?>
