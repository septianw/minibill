<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


$Q = buildSet($_REQUEST[prod],'id','products');

mysql_query($Q);

if (isset($_REQUEST[prod][id]))
$id = $_REQUEST[prod][id];
else $id = mysql_insert_id();

$msg = base64_encode("#00A000|#FFFFFF|Saved product ".$_REQUEST['prod']['title']);
header("Location: index.php?page=editProduct&id=$id&msg=$msg");

?>
