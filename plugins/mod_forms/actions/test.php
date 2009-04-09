<?php

$info = <<<__EOT__
This<Br />|one has|10
and<Br />|this one|50
and<Br /> another|may have|45
__EOT__;

$search = "one has";
preg_match_all("/(?P<question>.*?)\|(?P<value>.*?)\|(?P<score>.*?)$/m",$info,$matches);

print_r($matches);

?>
