<?php
/* MySQL Database Configuration */
$config['db_host']	= '';
$config['db_name']	= 'minibill';
$config['db_user']	= '';
$config['db_pass']	= '';


/* Security Configuration */
//secret_key:This should be difficult to "guess"
$config['secret_key']			= 'change me';

/* Path Information */
//session_save_path:Make sure this is read and writable by the webserver user
$config['session_save_path']	= '/tmp';

//template_cache_dir:Make sure this is read and writable by the webserver user
$config['template_cache_dir']	= '/tmp';

//END USER CONFIG

/***********************************************************/
/*                  :: W A R N I N G ::                    */
/* Do not change these unless you know what you are doing! */
/***********************************************************/
/**********************/
/* Path configuration */
/**********************/
$mycwd = getcwd();

//...... Change where the templates live.
$config['template_dir']			= 'templates/';
$config['page_dir']				= 'pages/';
$config['action_dir']			= 'actions/';
$config['include_dir']			= 'include/';
$config['plugin_dir']			= 'plugins/';

/****************************/
/* Admin Path Configuration */
/****************************/
$config['admin']['plugin_dir']			= '../plugins/';
$config['admin']['template_dir']		= 'templates/';
$config['admin']['page_dir']			= 'pages/';
$config['admin']['action_dir']			= 'actions/';
$config['admin']['include_dir']			= '../include/';

?>
