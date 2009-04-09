<?php

$ord = new OrderClass($user_id,0,$config);
$order_count = $ord->delOrder($_REQUEST['id']);
$msg = base64_encode("#00A000|#FFFFFF|Order {$_REQUEST['order']['id']} Deleted");

//..... Redirect to order group listing
//..... if we have more than 1 order in the list
if ($_REQUEST['uniq_id'])
{
	$redirect_to = ("index.php?page=showGroup&uniq_id=$_REQUEST[uniq_id]&msg=$msg");
}
//...... Or just redirect back to the ledger if the group is gone
else
{
	$redirect_to = ("index.php?page=ledger&msg=$msg");
}



?>
