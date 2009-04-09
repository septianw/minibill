<?php
global $data;

print "* Disabling any file downloads connected to this order\n";
foreach ($data as $user_id => $val)
{

	foreach($data[$user_id]['items'] as $num=>$item)
	{
		$Q="SELECT file_id FROM mod_download WHERE product_id='{$item['product_id']}'";
		list($files[]) = mysql_fetch_row(mysql_query($Q));
		print_r($files);
	}

	foreach($files as $file_id)
	{
		$Q="UPDATE mod_download_data SET hidden='1' WHERE user_id='$user_id' AND file_id='$file_id'";
		mysql_query($Q);
		print mysql_error();
	}
}

/*
Array
(
    [1] => Array
        (
            [user] => Array
                (
                    [id] => 0000000001
                    [email] => demo@ultrize.com
                    [password] => demo
                    [firstname] =>
                    [lastname] =>
                    [address] =>
                    [city] =>
                    [state] =>
                    [country] =>
                    [zipcode] =>
                    [phone] =>
                    [comments] =>
                    [cardnum] =>
                    [cc_exp_yr] => 0
                    [cc_exp_mo] => 0
                    [user_stamp] => 0000-00-00 00:00:00
                    [last_purchase_stamp] => 0000-00-00 00:00:00
                )

            [items] => Array
                (
                    [0] => Array
                        (
                            [order_id] => 510
                            [product_id] => 55
                            [uniq_id] => 93fb840126
                            [amount] => 9.95
                            [quantity] => 1
                            [date_purchased] => 2005-09-29 09:46:24
                            [user_id] => 1
                            [last_billed] => 2005-09-29
                            [payment_due] => 2005-09-29
                            [purchased_from] =>
                            [recurring] => 30
                            [status] => due
                            [mailer] =>
                            [payfail] => 0
                            [contract] => 0
                            [ip] => 203.162.3.146
                            [id] => 0000000001
                            [email] => demo@ultrize.com
                            [password] => demo
                            [firstname] =>
                            [lastname] =>
                            [address] =>
                            [city] =>
                            [state] =>
                            [country] =>
                            [zipcode] =>
                            [phone] =>
                            [comments] =>
                            [cardnum] =>
                            [cc_exp_yr] => 0
                            [cc_exp_mo] => 0
                            [user_stamp] => 0000-00-00 00:00:00
                            [last_purchase_stamp] => 0000-00-00 00:00:00
                            [desc] => Web Hosting
                        )

                )

        )

)
*/

