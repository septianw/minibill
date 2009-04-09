<?php

function keyED($txt,$encrypt_key)
{
    $encrypt_key = md5($encrypt_key);
    $ctr=0;
    $tmp = "";
    #print "TXT: ".strlen($txt)."\n";
    #print "ENC: ".strlen($encrypt_key)."\n";
    #print "B64: ".base64_encode($encrypt_key)."\n";
    for ($i=0;$i<strlen($txt);$i++)
    {
        if ($ctr==strlen($encrypt_key)) $ctr=0;
        $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
        $ctr++;
    }
    return $tmp;
}

function rmd5_encrypt($txt,$key)
{
    srand((double)microtime()*1000000);
    $encrypt_key = md5(rand(0,32000));
    $ctr=0;
    $tmp = "";

    for ($i=0;$i<strlen($txt);$i++)
    {
        if ($ctr==strlen($encrypt_key)) $ctr=0;
        $tmp.= substr($encrypt_key,$ctr,1) .
        (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
        $ctr++;
    }
    return keyED($tmp,$key);
}

function rmd5_decrypt($txt,$key)
{
    $txt = keyED($txt,$key);
    $tmp = "";
    for ($i=0;$i<strlen($txt);$i++)
    {
        $md5 = substr($txt,$i,1);
        $i++;
        $tmp.= (substr($txt,$i,1) ^ $md5);
    }
    return $tmp;
}

//Encrypt Function
function data_encrypt($encrypt,$key)
{
    if (!strlen($key)) return($encrypt);
    if (function_exists("mcrypt_create_iv"))
    {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $encrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt, MCRYPT_MODE_ECB, $iv);
    }
    else
    {
        $encrypt = rmd5_encrypt($encrypt,$key);
    }
    $encode = base64_encode($encrypt);
    return $encode;
}

//Decrypt Function
function data_decrypt($decrypt,$key)
{
    if (!strlen($key)) return($decrypt);
    $decoded = base64_decode($decrypt);
    if (function_exists("mcrypt_create_iv"))
    {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv);
    }
    else
    {
        $decrypt = rmd5_decrypt($decoded,$key);
    }
    return rtrim($decrypt);
}

?>
