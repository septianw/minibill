<?php

if (count($_SESSION['prods']))
{
	foreach($_SESSION['prods'] as $prod)
	{
		/***************************************/
		/* Compatibility with mod_forms plugin */
		/***************************************/
		if (isset($prod['formsCompleted']))
		{
			$i=0;
			foreach($prod['formsCompleted'] as $form_id=>$items)
			{
				unset($answers);
				$Q="SELECT name FROM mod_forms WHERE id='$form_id' LIMIT 1";
				list($formName) = mysql_fetch_row(mysql_query($Q));
				$forms[$i]['title'] = $formName;
				$forms[$i]['id']    = $form_id;
				foreach($items as $pages)
				{
					foreach($pages as $answers)
					{
						foreach($answers['answers'] as $q_id=>$q_vals)
						{
							$Q="SELECT q_caption FROM mod_forms_question WHERE id='$q_id' LIMIT 1";
							list($q_caption) = mysql_fetch_row(mysql_query($Q));
							$finalAnswers[] = array('id'=>$q_id,'title'=>$q_caption,'answers'=>$q_vals);
						}
					}
				}
				$forms[$i]['answers'] = $finalAnswers;
				$i++;
			}
			getProd($prod['id']);
			updateProd($prod['id'],'forms',$forms);
		}
		/********************************/
	}
}
?>
