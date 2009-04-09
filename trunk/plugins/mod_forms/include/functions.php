<?php


function getMinMaxPrice($form_id)
{
    $Q="SELECT id,q_type,q_values
        FROM mod_forms_question
        WHERE id='$form_id'";
    $res = mysql_query($Q);
    while($info = mysql_fetch_assoc($res))
    {
        print_r($info);
    }
}

function getPriceFor($session_id,$form_id)
{
    $Q="SELECT SUM(price) as price
        FROM mod_forms_answer
        WHERE session_id='$session_id'
        AND form_id='$form_id'";

    $res = mysql_query($Q);
    list($price) = mysql_fetch_row($res);
    return($price);
}

function getPrice($id,$value)
{
	$Q="SELECT q_values FROM mod_forms_question WHERE id='$id' LIMIT 1";
	list($info) = mysql_fetch_row(mysql_query($Q));
	preg_match("/(?P<question>.*?)\|".$value."\|(?P<score>.*?)$/m",$info,$matches);
	return($matches['score']);
}

function getQuestions($id)
{
	$Q="SELECT q_values,q_caption FROM mod_forms_question WHERE id='$id' LIMIT 1";
	list($info,$caption) = mysql_fetch_row(mysql_query($Q));
	
	preg_match_all("/(.*?)\|(.*?)\|([0-9].*?)/m",$info,$matches);

	if (count($matches[1]))
	{
		$i = 0;
		foreach($matches[1] as $q)
		{
			if (!strlen($matches[1][$i])) $matches[1][$i] = $caption;
			$i++;
		}
	}
	else $matches[1][0] = $caption;

	$m['questions'] = $matches[1];
	$m['values']	= $matches[2];
	$m['scores']	= $matches[3];

	return($m);
}

function getQuestion($id,$idx)
{
	$m = getQuestions($id);
	
	$match['question']	= $m['questions'][$idx];
	$match['value']		= $m['values'][$idx];
	$match['score']		= $m['scores'][$idx];

	return($match);
}

?>
