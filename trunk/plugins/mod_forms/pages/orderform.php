<?php

//..... mod_cart will probably set this...
if (!$_REQUEST['verified'])
{
	$orderids = split(',',$_REQUEST['order']);
	foreach($orderids as $id)
	{
		list($product_id,$quantity) = split('-',$id);
		$product_id = trim($product_id);
	}

	$Q="SELECT ask,askfreq,id 
		FROM mod_forms 
		WHERE product_id='$product_id' 
		AND ask='before'";

	$res = mysql_query($Q);

	while($info = mysql_fetch_assoc($res))
	{
		$form_id = $info['id'];

		$_SESSION['lastUrl'] = $_SERVER['REQUEST_URI'];
		$_SESSION['form_item'] = $product_id;

		//...... Check the complete form session
		foreach($_SESSION['prods'] as $prod)
		{
			if ($prod['id'] == $product_id)
			{
				$fc = count($prod['formsCompleted'][$form_id]);
				$pc = $prod['quantity'];
				break;
			}
		}

		//...... Set the complete bit so we know when we're finished .. or not
		$complete = (($fc >= $pc) || ($fc == 1 && $info['askfreq'] == 'once'));

		if (!$complete)
		{
			session_write_close();
			header("Location: index.php?page=form&form_id=$form_id&product_id=$product_id");
			exit();
		}
	}
}


?>
