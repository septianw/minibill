<?php

include("include/affiliate.class.php");
$af = new affiliate($_COOKIE['afid']);
$rip_total = $af->getCommission($_SESSION['grand_total']);
$af->setOrder($_SESSION['order_id'],$_SESSION['user']['id'],$rip_total);

?>
