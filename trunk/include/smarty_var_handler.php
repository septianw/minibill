<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/


function var_get_template ($tpl_name, &$tpl_source, &$smarty_obj)
{
	$tpl_source = $tpl_name;
	return true;
}

function var_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
{
	//...... Always parse variable streams!?
	$tpl_timestamp = time();
	return true;
}

function var_get_secure($tpl_name, &$smarty_obj)
{
    // assume all templates are secure
    return true;
}

function var_get_trusted($tpl_name, &$smarty_obj)
{
    // not used for templates
}

// register the resource name "var"
$X->register_resource("var", array("var_get_template",
                                       "var_get_timestamp",
                                       "var_get_secure",
                                       "var_get_trusted"));
?>
