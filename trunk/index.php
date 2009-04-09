<?php
ini_set('register_globals','off');

define('LOADED',1);
/* tabstop = 4 */
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/***********************************************/
/* Code Subject to GPL Licensing               */
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/
/*********************************************************************/
/* This file is the main switch file.                                */
/* Every function action, plugin ETC gets run through this file.     */
/* in an effort to ease expandibility, and security of the script,   */
/* there are two sections:  One is the "non-authenticated" functions */
/* and the other is the "Authenticated" functions.                   */
/* These are determined by whether or not the $LOGGED_IN variable is */
/* set.  The $ADMIN_MODE variable being set, would enable admin mode */
/* rights and change all paths to correlate to admin files and       */
/* templates                                                         */
/*********************************************************************/
//...... Version information
$config['version']['major']     = '1';
$config['version']['minor']     = '2';
$config['version']['build']     = '7';
$config['version']['release']   = 'Beta';
/**********************************************************************/

error_reporting(E_ALL ^ E_NOTICE);
//if (!$_REQUEST['nogz']) ob_start('ob_gzhandler');

//...... Set it up so these vars exist
$ADMIN_MODE = 0;
$LOGGED_IN	= 0;

//...... Set the default page
$DEFAULT	= 'login';
$PROJECT	= 'minibill';


$thisPage	= '';
$thisAction	= '';
$thisPlugin	= '';
$pluginName = '';


//...... Detect admin mode, and login information
if(preg_match("/\/admin\//",$_SERVER['SCRIPT_NAME']))
{
	include("../config.php");
	include($config['include_dir']."checkSettings.php");
	foreach($config['admin'] as $key=>$value) $config[$key] = $value;
	$ADMIN_MODE = 1;
}
else include("config.php");

if ($config['session_save_path']) session_save_path($config['session_save_path']);

if ($config['session_save_path'] && !is_writable($config['session_save_path']))
{
    print "<h1>Your sessions cannot be saved to &quot;{$config['session_save_path']}&quot;</h1>";
    print "<hr><h3>Please make sure that directory is writable by the webserver</h3>";
    print "<b>This can be edited in &quot;config.php&quot; : session_save_path</b>";
    $fatal_errors++;
}

if (isset($config['template_cache_dir']) && !is_writable($config['template_cache_dir']))
{
    print "<h1>Smarty cannot write to &quot;{$config['template_cache_dir']}&quot;</h1>";
    print "<hr><h3>Please make sure that directory is writable by the webserver</h3>";
    print "<b>This can be edited in &quot;config.php&quot; : template_cache_dir</b>";
    $fatal_errors++;
}

if ($fatal_errors) exit();

session_start();

//..... Set up stylization (for site consistancy)
if ($_GET['fromPage'] || $_SESSION['style']) 
{
	$_SESSION['style'] = (isset($_GET['fromPage'])) ? basename($_GET['fromPage'])."/" : $_SESSION['style'];
	$X->compile_id = $_SESSION['style'];
}

//...... Smarty templating engine
include($config['include_dir'].'Smarty.class.php');
$X = new Smarty();
$X->template_dir    =  $config['template_dir'];
$X->compile_dir     =  $config['template_cache_dir'];


//...... Set up demo mode variables
if(preg_match("/\/(demo|dev)\//",$_SERVER['SCRIPT_NAME'])) 
{
	$DEMO_MODE = 1;
	$X->assign('DEMO_MODE',$DEMO_MODE);
}

//...... Loaded from config.php (This could change as things progress)
$X->assign('config',$config);

if (!$_GET['showErrors']) $X->error_reporting = 0;
include($config['include_dir'].'smarty_db_handler.php');

/* Load sundry custom functions */
include($config['include_dir'].'functions.php');

/* For best results, have MCRYPT installed */
/* Load encryption routines */
include($config['include_dir'].'encryption.php');

//...... Include AJAX stuff (Future expansion)
//...... http://www.xajaxproject.org
include($config['include_dir']."xajax.inc.php");

//...... Credit card string validation class
include($config['include_dir'].'credit_card.class.php');

//...... Handle passed messages
include($config['include_dir'].'system_message.class.php');

//...... Sysetm Message display handler (With rounded corners)
$sysMsg = new Messages();
$sysMsg->setImgPath(($ADMIN_MODE == 1) ? "../img/" : "img/");

//...... This is for any messages passed on 
$sysMsg->handleRequest($_REQUEST['msg']);

/****************************************************/
/* CHECK FOR INSTALL                                */
/* This little snippet allows installtion handlers  */
/****************************************************/
//...... We can't load these while we are installing
if ($_SESSION['installing'])
{
	//...... Should only ever do this if installing.
	$NON_AUTH_PAGES		.= 'install|';
	$NON_AUTH_ACTIONS	.= 'install|';
}
else
{
	include($config['include_dir'].'dbConnect.php');
	include($config['include_dir'].'initPlugins.php');
	include($config['include_dir'].'order.class.php');
}
/****************************************************/

/******************************************/
/* CHECKS FOR SECURITY AND AUTHENTICATION */
/******************************************/
//...... Make my action and page requests SANE
//...... This logic only allows one type (page or request) at a time
if		($_REQUEST['page']) 	$thisPage	= basename($_REQUEST['page']);
elseif	($_REQUEST['action'])	$thisAction = basename($_REQUEST['action']);
else 							$thisPage	= ($_SESSION['installing']) ? "install" : $DEFAULT; // display default page

//...... We won't be needing these after the above.
unset($_REQUEST['action']);
unset($_REQUEST['page']);

//...... Check for login session
if (($_SESSION['mbadmin']	&& $_SESSION['mbpass']		&& $ADMIN_MODE) || 
	($_SESSION['email']		&& $_SESSION['password']	&& !$ADMIN_MODE)) $LOGGED_IN = 1;

//...... Sets smarty compile cache 
if ($ADMIN_MODE == 1) $X->compile_id='admin';


/****************************************************/
/* The default (static) minibill actions and pages  */
/* which are allowed non-authenticated              */
/* Plugins will add their own in their config.php   */
/****************************************************/
$NON_AUTH_ACTIONS 	.= 'purchase|login|lost_password|logout|reset|ipn';
$NON_AUTH_PAGES 	.= "lost_password|login|orderform";

//...... This logic hides templates from prying eyes if admin NOT logged in
if (($ADMIN_MODE && !$LOGGED_IN) && !isset($thisAction)) $thisPage = $DEFAULT;

if (strlen($thisPage)   && !$LOGGED_IN && !preg_match('/^(?:'.$NON_AUTH_PAGES.')/',$thisPage))
{
	$sysMsg->addMessage('<div align="center">Please log in to use those features.</div>');
	$thisPage = $DEFAULT;
}

if (strlen($thisAction) && !$LOGGED_IN && !preg_match('/^(?:'.$NON_AUTH_ACTIONS.")/",$thisAction)) 
{
	$sysMsg->addMessage('<div align="center">Please log in to use those features.</div>');
	$thisPage = $DEFAULT;
	unset($thisAction);
}
/****************************************************/

/*************************************/
/* Set up some default template vars */
/*************************************/
if (strlen($thisPage)) 
{
	//...... Adding nohead=1 to query string will 
	//...... suppress headers/footers - great for popups.
	$X->assign('nohead',$_REQUEST['nohead']);
	$X->assign('thisPage',$thisPage);
	$X->assign('config',$config);

	//...... Stuff login status into template
	$X->assign('ADMIN_MODE',$ADMIN_MODE);
	$X->assign('LOGGED_IN',$LOGGED_IN);
}
/*************************************/


/***********************************/
/* All things "authenticated" here */
/***********************************/
if ($LOGGED_IN)
{
	if (strlen($thisAction))
	{
		/*************************************/
		/* Authenticated Actions happen here */
		/*************************************/
		/********************************************/
		/* Loads up the modules, pages  and plugins */
		/********************************************/
		includeModules('actions',$thisAction,$X);
		/********************************************/

		//...... in your script: set $redirect_to to where you need 
		//...... to redirect back to instead of doing a header/exit.
		if (strlen($redirect_to)) 
		{
			session_write_close();
			header('Location: '.$redirect_to);
			exit();
		}
	}

	if (strlen($thisPage))
	{
		//...... this will completely bypass the menu system;
		$X->assign('nomenu',$_GET['nomenu']);

		/*******************************************/
		/* Loads up the modules, pages and plugins */
		/*******************************************/
		includeModules('pages',$thisPage,$X);
		/********************************************/

		/***************************************************/
		/* Loads menu builder functions, plugin menu items */
		/***************************************************/
		include($config['include_dir']."menu_builder.php");
		/***************************************************/

		if (isset($submenu)) $X->assign('submenu',$submenu);

		/****************************************************/
		/* Fetch a blank template if there is no page data. */
		/****************************************************/
		if (!strlen($pageData)) $pageData = $X->fetch("blank.html");
	}
}
else
{
	/******************************************/
	/* NON Authenticated ACTIONS happen here. */
	/******************************************/
	//...... Actions must happen before pages so actions can 
	//...... point back to pages properly.
	if (strlen($thisAction))
	{
		if (preg_match("/^(?:".$NON_AUTH_ACTIONS.")/",$thisAction))
		{
			/********************************************************/
			//NOTE: success|fail plugins are loaded in purchase.php */
			/* Loads up the modules, pages  and plugins             */
			/********************************************************/
			includeModules('actions',$thisAction,$X);
			/********************************************************/

			if (strlen($redirect_to)) 
			{
				session_write_close();
				header('Location: '.$redirect_to);
				exit();
			}
		}
	}

	/****************************************/
	/* NON- Authenticated Pages happen here */
	/****************************************/
	if (strlen($thisPage))
	{
		if (preg_match("/^(?:".$NON_AUTH_PAGES.")/",$thisPage))
		{
			/********************************************/
			/* Loads up the modules, pages  and plugins */
			/********************************************/
			includeModules('pages',$thisPage,$X);
			/********************************************/
			if (!strlen($pageData)) $pageData = $X->fetch("blank.html");
		}
	}
}

/***************************************************************************/
/* All the page stuff is done, finally display the default layout template */
/***************************************************************************/
if (strlen($thisPage) && strlen($pageData))
{
	if (isset($sysMsg->messages)) $X->assign('messages',$sysMsg->messages);

	$X->assign('pageData',$pageData);
	$X->assign('plugins',$plugin);

	$X->display("{$_SESSION['style']}default_layout.html");
}
?>
