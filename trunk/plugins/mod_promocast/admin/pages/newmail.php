<?php
include("include/catSelect.class.php");
$cats = new TemplateCats('promocast');

$cats->getLevel($_GET['id']);

$X->assign('cats',$cats->catLayout);
$X->assign('catName',$cats->getName($_GET['id']));

$hijackTemplate = 1;

?>
