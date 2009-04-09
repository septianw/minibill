<?php
class TemplateCats
{
	var $newLevel	= 0;
	var $catLayout	= '';
	var $dbName		= '';
	var $depth		= 0;

	function TemplateCats($dbName)
	{
		$this->dbName = $dbName;	
	}

	// Recursive function ...
	function getLevel($pid)
	{
		$nextPids = '';
		$thesePids = '';

		//...... See if you can get this query to count even the "0" size cats
		$Q="SELECT 
				tc.title,tc.id
			FROM 
				$this->dbName.template_cats tc,$this->dbName.template_data td
			WHERE 
				tc.parent_id='$pid'
			GROUP BY 
				tc.title";

		// will have to recurse 1 at a time (for each cat)
		$res = mysql_query($Q);
		print mysql_error();
		$numResults = mysql_num_rows($res);

		if ($this->depth == 0) $this->catLayout = "<div class=\"templatediv\"><ul id=\"templateTree\">";
		if ($numResults && $this->depth++) $this->catLayout .= "<ul>";
		while($info = mysql_fetch_assoc($res))
		{
			$this->catLayout .= "<li>";
			$this->catLayout .= "<a href=\"index.php?page=newmail&id={$info['id']}\">{$info['title']}</a>";
			$this->getLevel($info['id']);
			$this->catLayout .= "</li>\n";
		}
		if ($numResults && $this->depth--) $this->catLayout .= "</ul>";
		if ($this->depth == 0) $this->catLayout .= "</ul></div>";
		return;
	}
	function getName($id)
	{
		$Q="SELECT title FROM $this->dbName.template_cats WHERE id='{$id}' LIMIT 1";
		list($name) = mysql_fetch_row(mysql_query($Q));
		return($name);
	}
}

/*
$cats = new TemplateCats();
$cats->getLevel(0);
print $cats->catLayout;
*/

?>
