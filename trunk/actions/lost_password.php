<?php

if ($_REQUEST['email'])
{
    $Q="SELECT password FROM users WHERE email='".addslashes($_REQUEST['email'])."' LIMIT 1";
    list($password) = mysql_fetch_row(mysql_query($Q));

    if ($password)
    {
        $X->assign('email',$_REQUEST['email']);
        $X->assign('password',$password);
        $passwdMsg = $X->fetch('password_msg.html');
        mail($_REQUEST['email'],'Lost Password Request',$passwdMsg,"From: ".$config['help']['support_email']." <".$config['help']['support_email'].">");
        $msg = base64_encode('#00A000|#FFFFFF|Password information has been sent to your email account!<br />Please check your inbox in a few minutes.');
    }
    else
        $msg = base64_encode('#DA0000|#FFFFFF|Email address information not found.');
}

if ($_POST['nohead'])
{
	print "<h1 style='text-align:center;font-family:arial'>An email with your password has been sent!</h1>";
	print "<script>setTimeout('self.close()',5000);</script>";
}
else
{
	header("Location:index.php?msg=$msg&nohead={$_POST['nohead']}");
}

?>
