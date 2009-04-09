<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


$Q = buildSet($_REQUEST[cat],'id','category');
mysql_query($Q);

if (isset($_REQUEST[cat][id]))
$id = $_REQUEST[cat][id];
else $id = mysql_insert_id();
                                                                                
$msg = base64_encode("#00A000|#FFFFFF|Category Saved.");
header("Location: index.php?page=editCategory&id={$id}&msg={$msg}");


?>
