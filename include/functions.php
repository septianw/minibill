<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/             */
/***********************************************/


function ucitems($items)
{
    foreach ($items as $key=>$value)
    {
        $value = ucwords($value);
        $newItems[$key] = $value;
    }
    return($newItems);
}

function decompress($realFile)
{
    global $test;
    global $config;
    global $_SESSION;
    if (strtolower(substr($realFile,(strlen($realFile) - 4),strlen($realFile))) == ".zip")
    {
        $fullPath = "{$config['global']['upload_dir']}/";

        $inFile     = $fullPath.basename($realFile);
        $outFile    = $inFile.".txt";

        $cmd = ("unzip -ap $inFile > $outFile");
        if ($test) print $cmd."\n";
        print `$cmd`;
        return(basename($realFile).".txt");
    }
    else return($realFile);
}


function validate_email($str)
{
    $str = strtolower($str);
    if(ereg("^([^[:space:]]+)@(.+)\.(ad|ae|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gov|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nato|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$",$str))
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

function is_on($str)
{
	return(preg_match('/true|on|1|yes|enabled/i',$str));
}

function loadSqlData($sqlFile)
{
    //...... Loads the sql data file
    if ($fd = fopen($sqlFile,"r"))
    {
        while($Q = fgets($fd,2048))
        {
            if (preg_match("/^--/",trim($Q)))
                continue;
            else
                $qry[$x] .= $Q;
            if (preg_match("/;$/",trim($Q))) $x++;

        }

        //..... Gets rid of empty query
        unset($qry[$x]);

        //..... Go through each query and send them to mysql
        foreach($qry as $Q)
        {
            $Q=trim($Q);
            if ($Q)
            {
                mysql_query($Q);
            }

            if (mysql_error())
            {
                return(-1);
            }
        }
        return (count($qry) > 0) ? 1 : 0;
    }
    else return(0);
}


function print_pre($str)
{
	print "<pre>";
	print_r($str);
	print "</pre>";
}


//...... These functions should belong in a class somewhere eh?
//...... Gets the values of a product id from session.
function getProd($id)
{
	if ($id)
	{
		if (isset($_SESSION['prods']))
		{
			foreach($_SESSION['prods'] as $count=>$prod)
			{
				if ($prod['id'] == $id)
				{
					return($prod);
				}
			}
		}
	}
	return(NULL);
}
	

//...... nice little function to manage products in session array.
/* Updates the product list in session */
/* No key, updates the entire array with $value (assoc) */
function updateProd($id,$key,$value)
{
	$found =0;
	if ($id)
	{
		//...... Update a product in the cart
		if (isset($_SESSION['prods']))
		{
			foreach($_SESSION['prods'] as $count=>$prod)
			{
				if ($prod['id'] == $id)
				{
					$found = 1;
					if (strlen($key))
						$_SESSION['prods'][$count][$key] = $value;
					else 
						$_SESSION['prods'][$count] = array_merge($_SESSION['prods'][$count],$value);
					break;
				}
			}
		}
	}
	//...... Add a product to the cart
	if (!$found)
	{
		$_SESSION['prods'][] = $value;
	}
	return($found);
}

/* Applies a coupon to the given total amount */
function applyCoupon($coupon,$grand_total)
{
	if ($coupon['type'] == 'amount') 
		$grand_total = $grand_total - ($_SESSION['coupon']['amount']);
	elseif ($coupon['type'] == 'percent') 
		$grand_total = $grand_total - ceil(($grand_total * ($_SESSION['coupon']['amount'] / 100)));

	return($grand_total);
}

/* Builds the data SET query for updates or inserts */
function buildSet($vars,$whereid,$tableName)
{
	$is_update =0;
	foreach($vars as $key=>$val)
	{
		if ($key == $whereid)
		{
			$is_update =1;
			$WHERE = "WHERE $whereid='$val'";
			continue;
		}
		$set .= "$key='".addslashes($val)."',";
	}
	$set = substr($set,0,strlen($set)-1);

	if ($is_update)
	{
		$Q="UPDATE $tableName SET $set $WHERE";
	}
	else
	{
		$Q="INSERT INTO $tableName SET $set";
	}

	return($Q);
}

//...... Query the database against a callback function. (can be an array)
function getResults($Q,$cb = '')
{
	$res = mysql_query($Q);
	if(mysql_error()) return(0);

	while($info = mysql_fetch_assoc($res))
	{
		if ($cb)
		{
			if (is_array($cb))
			{
				foreach($cb as $func)
				{
					$info = call_user_func($func,$info);
				}
			}
			else 
			{
				$info = call_user_func($cb,$info);
			}
		}
		$results[] = $info;
	}
	return($results);
}

function getEnumValues($Table,$Column)
{
   $dbSQL = "SHOW COLUMNS FROM ".$Table." LIKE '".$Column."'";
   $dbQuery = mysql_query($dbSQL);

   $dbRow = mysql_fetch_assoc($dbQuery);
   $EnumValues = $dbRow["Type"];

   $EnumValues = substr($EnumValues, 6, strlen($EnumValues)-8);
   $EnumValues = str_replace("','",",",$EnumValues);

   return explode(",",$EnumValues);
}

function time_left($seconds)
{
    $days = intval($seconds / 86400);
    $rem = fmod($seconds,86400);
    $hours = intval($rem / 3600);
    $rem = fmod($rem,3600);
    $minutes = intval($rem / 60);
    $rem = fmod($rem,60);
    $seconds = intval($rem);
    return (array('days'=>$days,'hours'=>$hours,'minutes'=>$minutes,'seconds'=>$seconds));
}

function is_admin_ip($ip_addr)
{
	global $config;
	$ips = split(",",$config[admin_ip_list]);
	foreach($ips as $ip)
	{
		if ($ip == $ip_addr) return(1);
	}
	return(0);
}


function is_email($email){
return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
}

function format_filesize($size)
{
    $sizes = Array('B', 'K', 'M', 'G', 'T', 'P', 'E');
    $ext = $sizes[0];
    for ($i=1; (($i < count($sizes)) && ($size >= 1024)); $i++)
    {
        $size = $size / 1024;
        $ext  = $sizes[$i];
    }
    return round($size, 2).$ext;
}


function includeModules($type = 'pages',$thisFile,&$X)
{
    global $PROJECT;
    global $plugin;
    global $config;
    global $hijackTemplate;
    global $redirect_to;
	global $lang;
	global $xajax;
	global $pageData;
	global $sysMsg;

	if (!strlen($thisFile)) return(0);

	//..... Set up Main File (full path)
	if ($type == 'pages')	$mainFile	= $config['page_dir']  ."$thisFile.php";
	if ($type == 'actions') $mainFile	= $config['action_dir']."$thisFile.php";

	/************************************************************************/
	/* NOTE:
	/* This xajax stuff should probably live in its own include             */
	/* And be included in the index.php file BEFORE any modules are loaded. */
	/************************************************************************/
	//...... Load main MiniBill ajax functions
	$xajax = new xajax('index.php?page='.$thisFile);
	include_once($config['include_dir']."xajax_".$PROJECT.".php");

	//...... Include AJAX stuff 
	//...... http://www.xajaxproject.org
	$X->assign("xajax_header",$xajax->getJavascript('include'));

	//...... We just need to run the xajax function .. Right?
	//...... Module xajax stuff is pre-loaded on initModules.
	if(isset($_REQUEST['xajax'])) 
	{
		$xajax->processRequests();
		exit();	
	}
	/************************************************************************/

	$X->assign('thisPage',$thisFile);

	//...... Include actual plugin files
	includePlugin('pre',$type,$thisFile,$X);
	/*****************************************/
	/* These (Kinda) happen at the same time */
	/*****************************************/
	if (file_exists($mainFile)) include_once($mainFile);
	includePlugin('',$type,$thisFile,$X);

	//...... Almost final plugin info
	$X->assign('plugins',$plugin);

	if (!$hijackTemplate) $pageData .= $X->fetch("$thisFile.html");
	/*****************************************/
	includePlugin('post',$type,$thisFile,$X);

	//...... All the final stuff for template display here
	$X->assign('plugins',$plugin);

	//...... Load the normal (non-plugin) template.
	//print_pre($plugin);
}

function getGrandTotal($prodList)
{
	foreach($prodList as $prod)
	{
		$gt += $prod['price'] * $prod['quantity'];
	}
	return($gt);
}


function includePlugin($prefix,$type,$thisFile,&$X)
{
	global $data;
    global $plugin;
    global $config;
    global $hijackTemplate;
    global $redirect_to;
	global $lang;
	global $DEMO_MODE;
	global $ADMIN_MODE;
	global $LOGGED_IN;
	global $thisPage;
	global $thisPlugin;
	global $pluginName;
	global $pageData;
	global $submenu;
	global $sysMsg;

	if (isset($config['plugins']))
	{
		foreach($config['plugins'] as $pluginName => $enabled)
		{
			$thisPlugin = $pluginName;
	//		print "Plugin: $pluginName Prefix: $prefix, Type: $type, File: $thisFile<br />";
			if($_SESSION['STANDALONE'] && $_SESSION['STANDALONE'] != $pluginName) continue;

			//..... Processing
			if (strlen($prefix)) $pre = $prefix.'_'; 
			else $pre = '';

			//...... the admin plugin will live in (plugin_dir)/admin/pages/
			if ($LOGGED_IN && $ADMIN_MODE) $admdir = "admin/";

			//...... Construct my plugin file name
			$pluginFile = "$type/$pre$thisFile.php";

			$lastWD = getcwd();
			if (is_dir($config['plugin_dir']."$pluginName/$admdir"))
			{
				$validDir = 1;
				chdir($config['plugin_dir']."$pluginName/$admdir");
			}
			else $validDir = 0;

			//...... Load Plugin
			//if ($_REQUEST['showErrors']) print $pluginFile."<br />";
			if(is_on($enabled) && $validDir)
			{
				if (file_exists($pluginFile)) 
				{
					$X->assign('pluginName',$pluginName);
					$X->assign('thisPlugin',$thisPlugin);
					//print __FILE__." - $pluginName :: $pluginFile<br />";
					include_once($pluginFile);
				}
				if (file_exists($X->template_dir."/$pre$thisFile.html"))
				{
					//...... store it So we can revert back to the old compile id
					$oldCompile		= $X->compile_id;

					//...... Keeps smartry compiled templates happy
					$X->compile_id	= (($ADMIN_MODE) ? "admin" : '') . $thisPlugin;

/*
output
   appendForm
   appendPage

pos
   leftSide
   rightSide
   above
   below
   navBelow
   navAbove

order
   [numeric]

data
   html / data to output
*/

//print $thisPlugin."->".$thisFile."->data = ".getcwd()."/$config[template_dir]$pre$thisFile.html<br />";


					$plugin[$thisPlugin][$thisFile]['data'] = $X->fetch("$pre$thisFile.html");
					if ($hijackTemplate) 
						$pageData .= $plugin[$thisPlugin][$thisFile]['data'];

					//...... Reset our smarty compile id back to original.
					$X->compile_id	= $oldCompile;
				}
			}
			chdir($lastWD);
		}
	}
	//print "END Plugin: $pluginName Prefix: $prefix, Type: $type, File: $thisFile<br />";
	//print_pre($plugin);
	
}

?>
