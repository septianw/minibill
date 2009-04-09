<?php

class ReadConf
{
	var $endConfigNotice	= '//END USER CONFIG';
	var $newLines			= array();
	var $newVars			= array();
	var $comments			= array();
	var $variables			= array();
	var $configEnd			= 0;

	//...... Initiates the config file
	function readConf($file)
	{
		$this->configFile = $file;
	}

	//...... Parses the entire config file
	function parseConf()
	{
		$fd = fopen($this->configFile,"r");
		while($line = fgets($fd,1024))
		{
			if (!$this->configEnd) $this->newLines[] = $this->parseLine($line);
			else $this->newLines[] = $line;
		}
		fclose($fd);
	}

	//...... Assigns variables to our new config array
	function assignVars($configArray)
	{
		$this->newVars = $configArray;
	}

	function getVars()
	{
		$fd = fopen($this->configFile,"r");
		while($line = fgets($fd,1024))
		{
			//..... Stops parsing when the end config notice is reached
			if (preg_match("~$this->endConfigNotice~i",$line)) return(1);

			//...... Parses out comments lines for descriptions of variables.
			if (preg_match('~^//~',$line)) 
			{
				$line = substr($line,2,strlen($line));
				list($ckey,$cstring) = explode(":",$line,2);
				if ($ckey && $cstring) $this->comments[$ckey] = trim($cstring);
			} 
			if (preg_match('~/\*(.*?)\*/~',$line,$secMatch))
			{
				$section = trim($secMatch[1]);
			}

			list($varname,$value) = explode("=",$line);

			if (preg_match('/\$config/',$line))
			{
				if ($varname && $value)
				{
					$value = preg_replace('~;$~','',$value);

					preg_match_all('/\[\'(.*?)\'\]/',$varname,$matches);
					foreach($matches[1] as $varkey)
					{
						$this->variables[$section][$varkey] = eval("return($value);");
					}
				}
			}
		}
	}

	//...... Parses the config.php line
	function parseLine($line)
	{
		list($varname,$value) = explode("=",$line);

		//..... Stops parsing when the end config notice is reached
		if (preg_match("~$this->endConfigNotice~i",$line)) 
		{
			$this->configEnd = 1;
			return($this->endConfigNotice."\n");
		}

		if (preg_match('/\$config/',$line))
		{
			if ($varname && $value)
			{
				preg_match_all('/\[\'(.*?)\'\]/',$varname,$matches);

				$newLine = "\$config";
				foreach($matches[1] as $varkey)
				{
					$varKeys .= "['$varkey']";
				}

				$newLine .= "$varKeys\t= ";

				$keys = '$this->newVars'.$varKeys;
				$value = eval("return($keys);");

				$newLine .= ("'$value';\n");
			}
		}
		else $newLine = $line;
		return($newLine);
	}

	//...... Writes the config file back (Returns 0 | 1)
	function writeConf()
	{
		$this->configFile = $this->configFile;
		if (is_writeable($this->configFile))
		{
			$fd = fopen($this->configFile,'w+');
			foreach($this->newLines as $line)
			{
				fputs($fd,$line);
			}
			fclose($fd);
			return(1);
		}
		else return(0);
	}

	//...... Just displays all the variables (debugging)
	function showVars()
	{
		foreach($this->newLines as $line)
		{
			print "$line";
		}
	}
}

//..... Example
/*
$_REQUEST['db_host'] = 'localhost';
$c = new ReadConf('config.php');
$c->assignVars($_REQUEST);
$c->parseConf();
$c->showVars(); // Show what we are writing
if ($c->writeConf()) print "Updates Managed!"; else print "Failed to write!";
*/

?>
