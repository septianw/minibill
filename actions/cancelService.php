<?php

$Q="SELECT * FROM users WHERE email='$_SESSION[email]' LIMIT 1";
$res = mysql_query($Q);
$user = mysql_fetch_assoc($res);

$Q="UPDATE orders SET recurring = 'No',status='cancel' WHERE order_id='$_REQUEST[order_id]' AND user_id='$user[id]' LIMIT 1";

print $Q;
$res = mysql_query($Q);

$redirect_to="index.php?page=history";
?>
