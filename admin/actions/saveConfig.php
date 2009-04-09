<?php

if (!($DEMO_MODE))
{
	//..... New Variables
	if (strlen($_POST['newvar']['value']))
	{
		$Q="REPLACE INTO config
			SET id='".$_POST['newvar']['id']."',
			variable='".$_POST['newvar']['variable']."',
			value='".$_POST['newvar']['value']."'";
		print $Q;
		mysql_query($Q);
		$msg .= base64_encode("#00A000|#FFFFFF|New variable added.");
	}

	//...... Existing Variables
	foreach ($_POST[conf] as $key=>$vars)
	{
		foreach($vars as $vkey=>$vval)
		{
			$Q="REPLACE INTO config 
				SET id='$key',
				variable='$vkey',
				value='".addslashes($vval)."'";
			mysql_query($Q);
		}
		$msg .= base64_encode("#00A000|#FFFFFF|Variables Saved.");
	}
}

header("Location: index.php?page=editConfig&id=".$_POST[newvar][id]."&msg=$msg");

?>
