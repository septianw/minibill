<?php

//...... Some helper functions for handling questions
include("include/functions.php");

//...... You should probably use responder.class.php

//...... Set up session to handle answers.
//if (!is_array($_SESSION['answers'])) $_SESSION['answers'] = array();

//...... Display PAGE
$pg = $_POST['pg'];

//...... Questions per page
$qpp = $_POST['qpp'];

//...... Next Question Index "page number" (LIMIT query)
$fp = $qpp * $pg;

//..... Get a list of questions on this page
$Q= "SELECT id,q_preg,q_values,q_isRequired 
	FROM mod_forms_question 
	WHERE form_id='$_REQUEST[form_id]' 
	ORDER BY q_order LIMIT ".$_SESSION['form_index'].",$qpp";
$res = mysql_query($Q);

//...... Yes, this has to be here .. these two UNSET's
unset($myPrice);
unset($_SESSION['myPrice'][$pg]);

//...... Go through each DB question
while($q = mysql_fetch_assoc($res))
{
	//...... Get values from each line
	$values	= split("\n",$q['q_values']);

	$qid	= $q['id'];
	$req	= $q['q_isRequired'];
	$preg	= $q['q_preg'];

	//...... Go through each value line
	foreach($values as $value_line)
	{
		$v = 0; // Index for value

		//...... If we have an array ... 
		if (is_array($_POST['answers'][$qid]))
		{
			//...... compare each submitted answer
			foreach($_POST['answers'][$qid] as $answer)
			{
				//...... Split out parameters from line values
				list($q,$a,$s) = split("\|",$value_line);

				//print "ID: $qid PREG: $preg / ANS: $answer / REQUIRED: $req<br />";
				//...... Check first value against regex && required
				
				//...... Regex Error checking / Required check
				if ($v == 0 && !preg_match("/$preg/sim",$answer) || (strlen($answer) == 0 && $req == 'yes'))  $errors[] = $qid;

				$v++;
			}	
		}
		//...... If nothing gets sent in .. i.e. checkbox, radio etc ... 
		//...... This makes required checkboxes and radio buttons error out correctly.
		if(strlen($_POST['answers'][$qid]) == 0 && $req == 'yes') $errors[] = $qid;
	}
}

//...... Create a nice "stuff" array
foreach($_POST['answers'] as $q_id=>$answers)
{
	foreach ($answers as $idx=>$value)
	{
		$qinfo = getQuestion($q_id,$idx);
		$stuff['answers'][$q_id][$idx]['score']		= $qinfo['score'];
		$stuff['answers'][$q_id][$idx]['value']		= $value;//..... Get this from the posted form
		$stuff['answers'][$q_id][$idx]['question']	= $qinfo['question'];
	}
}

//...... assign answers to session variables
$_SESSION['answers']["page_$pg"] = $stuff;

//...... Default redirection, everything is okay in the form
//$redirect_to = "index.php?page=form&product_id=".$_REQUEST['product_id']."&form_id=$_REQUEST[form_id]&fp=$fp";

//...... If detected errors in the form, set errorList var 
if ($errors) 
{
	//...... Set form pointer back to last page (page w/ error)
	$fp = $fp - $qpp;
	$_SESSION['form_index'] = $fp;
	foreach($errors as $q_id) $errorList .= "error[$q_id]=error&";
}

$redirect_to = "index.php?page=form&product_id=".$_REQUEST['product_id']."&form_id=$_REQUEST[form_id]&fp=$fp&$errorList";

//...... need to post this info
/***************************************/
//...... Final form page submitted
/***************************************/
if ($_POST['finish'] && !$errors)
{
	//...... Go through each answer and write it to the database
	foreach($_SESSION['answers'] as $pg=>$ans)
	{
		foreach($ans as $key=>$val)
		{
			foreach($val as $id=>$v)
			{
				foreach($v as $idx=>$val)
				{
					print_pre($val);
					print getPrice($id,$v);

					$Q="INSERT INTO mod_forms_answer 
						SET question_id='$id',
						form_id='$_REQUEST[form_id]',
						session_id='$_REQUEST[PHPSESSID]',
						user_id='$_SESSION[id]',
						answer='".addslashes($val['value'])."',
						answer_date=NOW(),
						price='".getPrice($i,$v)."'";

					mysql_query($Q);
				}
			}
		}
	}

	$form_id = $_REQUEST['form_id'];
	$product_id = $_SESSION['form_item'];

	$thisProd = getProd($product_id);

	if (isset($thisProd['formsCompleted'][$form_id]))
	{
		$formsCompleted = $thisProd['formsCompleted'];
		$formsCompleted[$form_id][] = $_SESSION['answers'];
	}
	else $formsCompleted[$form_id][0] = $_SESSION['answers'];

	updateProd($product_id,'formsCompleted',$formsCompleted);

	session_write_close();
	$redirect_to = "index.php?page=question_end&product_id=".$_REQUEST['product_id']."id=".$_REQUEST['form_id'];
	header("Location: $redirect_to");
	exit();
}

?>
