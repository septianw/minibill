<?php

//...... Set up definitions
$name           = 'mod_cart';
$enabled        = 'true';
$version        = '1.0';
$author         = 'Matthew Frederico';
$license        = 'GPL / Public';

//create the ultimate plugin array
$plugin[$name] = array( 'enabled'   =>$enabled,
                        'version'   =>$version,
                        'license'   =>$license,
                        'author'    =>$author);

//...... Auto-Installation details for module
if (is_on($config['plugins'][$name]))
{
	//...... Set up non authenticated actions (if applicable)
	$plugin[$name]['non_auth_actions'][]	= 'keepShopping';
	$plugin[$name]['non_auth_actions'][]	= 'updateQuantity';

	//...... Set up non authenticated pages (if applicable)
	$plugin[$name]['non_auth_pages'][]		= 'showCategories';
	$plugin[$name]['non_auth_pages'][]		= 'showProducts';
	$plugin[$name]['non_auth_pages'][]		= 'cart';
	$plugin[$name]['non_auth_pages'][]		= 'updateOrder';

	$installed	= '';
	$sqlFile	= '';
	$res		= '';
	$msg 		= '';
}

?>
