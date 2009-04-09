<?php

function parseCsv($string)
{
    $items = explode("\n",$string);
    $headKeys = explode(",",$items[0]);

    unset($items[0]);

    foreach($items as $item)
    {
        $i++;
        $csv = (csv_string_to_array($item));
        foreach($csv as $idx=>$value)
        {
            $key = trim($headKeys[$idx]);
            $newCsv[$i][$key] = ucwords($value);
        }
    }
    return($newCsv);
}

function csv_string_to_array($str)
{
   $expr="/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
   $results=preg_split($expr,trim($str));
   return preg_replace("/^\"(.*)\"$/","$1",$results);

}
/*
| id                  | int(10) unsigned zerofill |      | PRI | NULL                | auto_increment |
| email               | varchar(255)              |      | UNI |                     |                |
| password            | varchar(64)               |      |     |                     |                |
| firstname           | varchar(255)              |      |     |                     |                |
| lastname            | varchar(255)              |      |     |                     |                |
| address             | varchar(255)              |      |     |                     |                |
| city                | varchar(255)              |      |     |                     |                |
| state               | varchar(255)              |      |     |                     |                |
| country             | varchar(255)              |      |     |                     |                |
| zipcode             | varchar(255)              |      |     |                     |                |
| phone               | varchar(255)              |      |     |                     |                |
| comments            | text                      | YES  |     | NULL                |                |
| orderFrom           | varchar(64)               |      |     |                     |                |
| cardnum             | varchar(255)              |      |     |                     |                |
| cc_exp_yr           | int(4)                    |      |     | 0                   |                |
| cc_exp_mo           | int(2)                    |      |     | 0                   |                |
| user_stamp          | datetime                  |      |     | 0000-00-00 00:00:00 |                |
| last_purchase_stamp 
*/

$csv = (parseCsv($_REQUEST['csvData']));

$remote_key = 'e4f96536fb72d968abd693f1924ae173';
//insert the order into the database
foreach($csv as $user)
{
	if (strlen($user['s_zipcode']))
	{
		$country = (strlen($user['s_country'])) ? addslashes($user['s_country']) : 'USA';
		$Q="INSERT INTO users SET 
			email='".addslashes($user['s_firstname'].".".$user['s_lastname'].'@'.$user['orderid'])."',
			firstname='".addslashes($user['s_firstname'])."',
			lastname='".addslashes($user['s_lastname'])."',
			address='".addslashes($user['s_address'].' '.$user['s_address2'])."',
			city='".addslashes($user['s_city'])."',
			state='".addslashes($user['s_state'])."',
			country='$country',
			zipcode='$user[s_zipcode]'";

		$res = mysql_query($Q);
		$user_id = mysql_insert_id();

		$Q="SELECT * FROM mod_interface_prodLookup 
			AS mpl,products 
			WHERE remote_prod_id='$user[productcode]' 
			AND products.id=mpl.id";
		$res = mysql_query($Q);
		$product = mysql_fetch_assoc($res);

		$o = new OrderClass($user_id,'',$config,'IMPORT');
		$o->addItem($product['id'],$user['amount'],$user['price']);
		$o->postOrder();

		$Q="INSERT INTO mod_interface_data SET
			uniq_id='$o->uniq_id',
			remote_order_id='$user[orderid]',
			remote_key='$remote_key'";

		mysql_query($Q);
	}
}

header("Location: index.php?page=addCSV&msg=".base64_encode("#00A000|#FFFFFF|Imported okay!"));

?>
