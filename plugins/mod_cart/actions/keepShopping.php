<?php

session_write_close();
header("Location: ".$config['secure']['cart']);
exit();

?>
