<?php

//...... Allows you to have "multiple" 
//...... companies depedning on which directory it's in

//...... Set up definitions
$name           = 'mod_multiface';
$enabled        = 'true';
$version        = '1.0';
$author         = 'Matthew Frederico';
$license        = 'GPL / Public';

//create the ultimate plugin array
$plugin[$name] = array( 'enabled'   =>$enabled,
                        'version'   =>$version,
                        'license'   =>$license,
                        'author'    =>$author);

$multiface['pridehealth']['address']		= '4144 South';
$multiface['pridehealth']['city']			= 'West Valley';
$multiface['pridehealth']['state']			= 'UT';
$multiface['pridehealth']['zipcode']		= '84120';
$multiface['pridehealth']['phone']			= 'Toll Free: 1-866-799-FUEL';
$multiface['pridehealth']['office_hours']	= '9am - 5pm MST';
$multiface['pridehealth']['name']			= 'Pride Health Online';
$multiface['pridehealth']['contact_email']	= 'customercare@pridehealthoneline.com';
$multiface['pridehealth']['logo']			= 'img/pride_health_logo_321x70.gif';

//...... Auto-Installation details for module
if (is_on($config['plugins'][$name]))
{
	list(,$subdir) = preg_split('~/~',$_SERVER['PHP_SELF']);
	if (isset($multiface[$subdir]))
	{
		foreach($multiface[$subdir] as $key=>$val)
		{
			$config['company'][$key] = $val;
		}
	}
}

?>
