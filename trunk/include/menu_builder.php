<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

// NOTE
// A STANDALONE module is running in stand alone mode, 
// e.g.: no other modules should load except it when 
// it is accessed and initialized by the main kernel. 

function loadPluginMenus($pluginName,$thisPage,$type,$auth)
{
	global $config;
	global $menuitem;
	global $submenu;

	/********************************************/
	/* Loads up the plugin menu items for admin */
	/********************************************/
	if (is_array($config['plugins']))
	{
		foreach($config['plugins'] as $pluginName=>$pluginStatus)
		{
			if(is_on($pluginStatus))
			{
				//...... Load any main menu items for this plugin / page combo
				$menuFile = "{$config['plugin_dir']}$pluginName/{$auth}{$type}menu.php";
				if (file_exists($menuFile))
				{
					include($menuFile);
				}
			}
		}
	}
}

$STANDALONE = ($_SESSION['STANDALONE'] || $_SESSION['STANDALONE'] == $pluginName);
if (!$STANDALONE)
{
	//...... If logged in as admin, load admin menu options
	if ($LOGGED_IN && $ADMIN_MODE)
	{
		loadPluginMenus($pluginName,$thisPage,'','admin/');
		//...... Load any submenus for this plugin / page combo
		loadPluginMenus($pluginName,$thisPage,'submenu_','admin/');
	}

	//...... User mode menu options
	else
	{
		loadPluginMenus($pluginName,$thisPage,'','');
		//...... Load any submenus for this plugin / page combo
		loadPluginMenus($pluginName,$thisPage,'submenu_','');
	}
}
//...... Standalone module mode
else
{
	loadPluginMenus($pluginName,$thisPage,'standalone_','',$STANDALONE);
	loadPluginMenus($pluginName,$thisPage,'submenu_','',$STANDALONE);
}

if (is_array($submenu)) $X->assign('submenu',$submenu);

$page = $thisPage;

/*********************************************************/
/* Menu Items - Can add and remove stock menu items here */
/*********************************************************/
include($config['page_dir']."menu.php");
/*********************************************************/

$width = intval(100 / (count($menuitem) + 1));
$X->assign('width',$width);
$X->assign('config',$config);
$X->assign('pagedesc',$menuitem[$page]);

if (is_array($menuitem))
{
	foreach ($menuitem as $item=>$desc)
	{
		$page = basename($page);
		if ($page == $item)
		{
			$_SESSION['page_cat'] = $page;
			break;
		}
	}

	foreach($menuitem as $item=>$desc)
	{
		$i++;
		$items[$i]['item'] = $item;
		$items[$i]['desc'] = $desc;
		if ($item == $page)
		{
			$_SESSION['page_cat'] = $page;
		}
		if ($item == $_SESSION['page_cat'])
		{
			$_SESSION['page_cat'] = $item;
			$items[$i]['img_type'] = '';
			$items[$i]['class'] = 'menutop';
		}	
		else
		{
			$items[$i]['img_type']	= '_lolight';
			$items[$i]['class']		= 'menutophi';
		}
	}
}

$X->assign('menu',$items);

?>
