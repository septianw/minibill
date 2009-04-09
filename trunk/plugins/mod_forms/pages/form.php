<?php

if (!isset($_SESSION['prods']))
{
	$msg = base64_encode('#A00000|#FFFFFF|<center>Please select some items for purchase!</center>');
	session_write_close();
	header("Location: index.php?page=cart&msg=$msg");
	exit();
}

$X->assign('nomenu',1);
//...... Set the form_id for later usage!
if (!isset($_SESSION['form_id'])) 
{
	$_SESSION['form_id'] = $_REQUEST['form_id'];
}

//...... Get form information
$Q="SELECT * FROM mod_forms WHERE id='$_REQUEST[form_id]' LIMIT 1";
$form = mysql_fetch_assoc(mysql_query($Q));

//...... Get number of questions
$Q="SELECT COUNT(*) FROM mod_forms_question WHERE form_id='$_REQUEST[form_id]'";
list($num_questions) = mysql_fetch_row(mysql_query($Q));

//...... the "FP" request comes in as the "NEXT" page ..  (View source)
//...... Allows us to jump pages
$_SESSION['form_index'] = $_REQUEST['fp'];

//...... Which page are we on?
if (!isset($_SESSION['form_index'])) $_SESSION['form_index'] = '0';

//...... Allow questions to access main form info, and session vars
$X->assign('form',$form);
$X->assign('session',$_SESSION);

//...... Pages / breadcrumb
$pg		= ceil(($_SESSION['form_index'] / $form['q_per_page']) + 1);
$numPg	= ceil($num_questions / $form['q_per_page']);

$X->assign('page',$pg);
$X->assign('num_pages',$numPg);
$X->assign('title',"Question page $pg / $numPg");

//...... Iterate through questions
//...... set defaults, or pre-filled info
$vals = 0;

//...... Get the questions for this page .. in order
$Q="SELECT * FROM mod_forms_question 
	WHERE form_id='$_REQUEST[form_id]' 
	ORDER BY q_order 
	LIMIT ".($_SESSION['form_index']).",$form[q_per_page]";

$res = mysql_query($Q);

//...... Makes sure we have answered the same amount of
//...... Questionairres as we have products in our cart
$product_id = $_REQUEST['product_id'];
$form_id = $_REQUEST['form_id'];

foreach($_SESSION['prods'] as $prod)
{
	if ($prod['id'] == $product_id)
	{
		$fc = count($prod['formsCompleted'][$form_id]);
		$pc = $prod['quantity'];
		break;
	}
}

//...... Makes sure we can't click "BACK" into the form
//...... After they are all completed
if ($fc + 1 > $pc)
{
	session_write_close();
	header("Location: index.php?page=cart");
	exit();
}

$X->assign('fc',($fc + 1));
$X->assign('pc',$pc);

while($question = mysql_fetch_assoc($res))
{
    $question['q_caption'] = stripslashes($question['q_caption']);

	//...... Get question values, terminated by "newlines"
	$lines = split("\n",$question['q_values']);
	$qid = $question['id'];

	//...... Sets the "error" class in the html block
	if($_REQUEST['error'][$qid] == 'error') $question[error] = 'q_error';

	//...... is this (possibly) a multi-selectable field?
	if(preg_match("/checkbox|multi-select|radio|dropdown|text/",$question[q_type]))
	{
		//...... Go through each question value and split out the parts
		foreach($lines as $line)
		{
			//..... A cheap way to see if we have a blank line.
			if(strlen($line) < 3) continue;
			list($text,$value,$price) = explode("|",$line);

			//...... Split out the line in parameters
			$values['values'][$vals]['text'] = stripslashes($text);
			$values['values'][$vals]['price'] = $price;
			$values['values'][$vals]['value'] = $value;

			//..... Match answers and "select"
			if (isset($_SESSION['answers']["page_$pg"]['answers'][$qid]))
			{
				foreach($_SESSION['answers']["page_$pg"]['answers'][$qid] as $value)
				{
					//...... set "checked / selected" here
					if ($value['value'] == $values['values'][$vals]['value'])
					{
						$values['values'][$vals]['selected'] = "SELECTED CHECKED";
					}
					
					if ($value['question'] == $values['values'][$vals]['text'])
						$values['values'][$vals]['value'] = htmlentities(stripslashes($value['value']));
					
				}
				
			}
			//...... Iterate to the next possible answer value
			$vals++;
		}
	}

	//...... Assign values to the questions html template
	
	//...... Stylizations, width height CSS stuff - rows/cols in textarea
	$X->assign('width','width:'.$question['q_width']);
	$X->assign('height','height:'.$question['q_height']);
	$X->assign('style',$question['q_style']);

	//...... Assign base question information
	$question['q_errortext'] = stripslashes($question['q_errortext']);

	$X->assign('question',$question);
	$X->assign('values',$values);

	$rendered_questions[$quest]['html'] .= $X->fetch('forms/header.html');
	$rendered_questions[$quest]['html'] .= $X->fetch('forms/'.$question['q_type'].'.html');
	$rendered_questions[$quest]['html'] .= $X->fetch('forms/footer.html');

	//...... reset these after question is rendered
	unset($values);
	$vals = 0;

	//...... Next question .. please
	$quest++;
}


//..... Rendered Questions
$X->assign('questions',$rendered_questions);

$hijackTemplate = 1;

?>
