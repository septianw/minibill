<?php
/**************************/
/* Database Configuration */
/**************************/
//>Database Configuration
$config['db_host']	= '';
$config['db_name']	= 'minibill_dev';
$config['db_user']	= 'root';
$config['db_pass']	= 'build4n0w';

$config['secret_key']			= 'abcdefg';
$config['session_save_path']	= '/home/ultrize/tmp/minibill/sessions';
$config['template_cache_dir']	= '/home/ultrize/tmp/minibill/templates_c';

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
