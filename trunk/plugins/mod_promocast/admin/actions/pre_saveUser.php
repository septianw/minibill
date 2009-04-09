<?php

if ($_REQUEST['user_id']) $_REQUEST['promoUser']['id'] = $_REQUEST['user_id'];

$Q=buildSet($_REQUEST['promoUser'],'id','promocast.users');

mysql_query($Q);

if (mysql_insert_id())
{
    $newUser = 1;
    $_REQUEST['promoUser']['id'] = mysql_insert_id();
}

/* REPLACE the credits row */
$Q="REPLACE INTO
        promocast.user_credits
    SET
        credits='{$_REQUEST['credits']}',
        promoCredits='{$_REQUEST['promoCredits']}',
        user_id='{$_REQUEST['promoUser']['id']}'";
mysql_query($Q);

?>
