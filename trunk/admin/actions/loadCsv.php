<?php

$file = "{$_REQUEST['file']}";
$fd = fopen($file,"r");

//...... Create a new list
$date = date("M/d/Y H:ia");

$Q="INSERT INTO 
		category 
	SET
		title='{$_REQUEST['title']}';
	";

$res = mysql_query($Q);
$cat_id = mysql_insert_id();

while($info = fgetcsv($fd,4096))
{
	$set = buildCSVSet($info,$_REQUEST['field']);
	if ($set)
	{
		$Q="INSERT INTO products
				SET $set category_id={$cat_id}";
		mysql_query($Q);

		$addName = mysql_insert_id();
		print mysql_error();
	}
}

function buildCSVSet($info,$cols)
{
	foreach($info as $id=>$value)
	{
		$set .= "{$cols[$id]}='".addslashes($value)."',";
	}
	return($set);
}

unlink($file);

$redirect_to="index.php?page=products";

?>
